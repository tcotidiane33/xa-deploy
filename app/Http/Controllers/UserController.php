<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\AccessControlService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ClientTransferNotification;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use App\Models\ClientHistory;

class UserController extends Controller
{
    protected $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
        $this->middleware(['auth', 'permission:edit users'])->only(['edit', 'update']);
        $this->middleware(['auth', 'permission:view users'])->only(['index', 'show']);
        $this->middleware(['auth', 'permission:delete users'])->only(['destroy']);
    }
    public function assignRole(Request $request, User $user)
    {
        return $this->accessControlService->assignRole($request, $user);
    }

    public function revokeRole(Request $request, User $user)
    {
        return $this->accessControlService->revokeRole($request, $user);
    }

    public function givePermission(Request $request, User $user)
    {
        return $this->accessControlService->givePermission($request, $user);
    }

    public function revokePermission(Request $request, User $user)
    {
        return $this->accessControlService->revokePermission($request, $user);
    }

    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();
        $breadcrumbs = [
            // ['name' => 'Fiches Clients', 'url' => route('dashboard')],
            ['name' => 'Utilisateurs', 'url' => route('users.index')],
        ];
        return view('users.index', compact('users', 'posts','tickets', 'breadcrumbs'));
    }

    public function create()
    {
        $roles = Role::all();
        $breadcrumbs = [
            ['name' => 'Utilisateurs', 'url' => route('users.index')],
            ['name' => 'Créer un utilisateur', 'url' => route('users.create')],
        ];
        return view('users.create', compact('roles', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('users.show', $user)->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        // Charger les relations nécessaires
        $user->load('roles', 'clientsAsGestionnaire', 'clientsAsResponsable', 'clientsAsBinome');

        // Récupérer les clients disponibles (non encore rattachés à l'utilisateur)
        $availableClients = Client::whereDoesntHave('users', function($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();

        // Récupérer les relations clients-utilisateur
        $userClients = DB::table('client_user')
            ->join('clients', 'client_user.client_id', '=', 'clients.id')
            ->where('client_user.user_id', $user->id)
            ->select('clients.*', 'client_user.role', 'client_user.created_at')
            ->get();

        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('dashboard')],
            ['name' => 'Utilisateurs', 'url' => route('users.index')],
            ['name' => $user->name, 'url' => '#']
        ];

        return view('users.show', compact('user', 'userClients', 'availableClients', 'breadcrumbs'));
    }


    public function edit(User $user)
    {
        $roles = Role::all();
        $clients = Client::all();
        $users = User::all(); // Ajoutez cette ligne pour récupérer tous les utilisateurs
        $breadcrumbs = [
            ['name' => 'Utilisateurs', 'url' => route('users.index')],
            ['name' => 'Modifier un utilisateur', 'url' => route('users.edit', $user)],
        ];
        return view('users.edit', compact('user', 'roles', 'clients', 'users', 'breadcrumbs'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name', // Utilisez 'name' au lieu de 'id'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        if (isset($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles']); // Utilisez syncRoles pour synchroniser les rôles

        return redirect()->route('users.show', $user)->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }


    public function manageClients(User $user)
    {
        // Récupérer tous les clients gérés par l'utilisateur avec leurs rôles et fiches
        $managedClients = Client::where(function($query) use ($user) {
            $query->where('gestionnaire_principal_id', $user->id)
                  ->orWhere('responsable_paie_id', $user->id)
                  ->orWhere('binome_id', $user->id);
        })->with(['ficheClients' => function($query) {
            $query->latest('updated_at');
        }])->get()->map(function($client) use ($user) {
            $role = null;
            if ($client->gestionnaire_principal_id == $user->id) {
                $role = 'gestionnaire';
            } elseif ($client->responsable_paie_id == $user->id) {
                $role = 'responsable';
            } elseif ($client->binome_id == $user->id) {
                $role = 'binome';
            }
            return [
                'client' => $client,
                'role' => $role,
                'fiche_client' => $client->ficheClients->first()
            ];
        });

        // Récupérer les utilisateurs disponibles pour le transfert
        $availableUsers = User::where('id', '!=', $user->id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['gestionnaire', 'responsable']);
            })->get();

        $periodesPaie = PeriodePaie::orderBy('created_at', 'desc')->get();
        $breadcrumbs = [
            ['name' => 'Tableau de bord', 'url' => route('dashboard')],
            ['name' => 'Utilisateurs', 'url' => route('users.index')],
            ['name' => $user->name, 'url' => route('users.show', $user)],
            ['name' => 'Gestion des clients', 'url' => '#']
        ];

        return view('users.manage_clients', compact('user', 'managedClients', 'availableUsers', 'periodesPaie', 'breadcrumbs'));
    }

    public function attachClient(Request $request, User $user)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'role' => 'required|in:gestionnaire,responsable,binome',
        ]);

        try {
            DB::beginTransaction();

            $client = Client::findOrFail($validated['client_id']);
            $client->attachUser($user->id, $validated['role']);

            // Envoyer une notification
            $user->notifyUserAssignment($user, $client, $validated['role']);

            DB::commit();
            return redirect()->back()->with('success', 'Client rattaché avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors du rattachement du client.']);
        }
    }

    public function updateClientRelation(Request $request, User $user)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'role' => 'required|in:gestionnaire,responsable,binome',
        ]);

        try {
            DB::beginTransaction();

            $client = Client::findOrFail($validated['client_id']);
            
            // Détacher l'ancien rôle
            $oldRole = DB::table('client_user')
                ->where('client_id', $client->id)
                ->where('user_id', $user->id)
                ->value('role');

            if ($oldRole) {
                switch ($oldRole) {
                    case 'gestionnaire':
                        $client->gestionnaire_principal_id = null;
                        break;
                    case 'responsable':
                        $client->responsable_paie_id = null;
                        break;
                    case 'binome':
                        $client->binome_id = null;
                        break;
                }
            }

            // Attacher le nouveau rôle
            $client->attachUser($user->id, $validated['role']);
            
            DB::commit();
            return redirect()->back()->with('success', 'Relation mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la relation.']);
        }
    }

    public function detachClient(Request $request, User $user)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'role' => 'required|string|in:gestionnaire,binome,responsable',
        ]);

        $client = Client::find($request->client_id);

        switch ($request->role) {
            case 'gestionnaire':
                if ($client->gestionnaire_principal_id == $user->id) {
                    $client->assignGestionnairePrincipal(null);
                }
                break;
            case 'binome':
                if ($client->binome_id == $user->id) {
                    $client->assignBinome(null);
                }
                break;
            case 'responsable':
                if ($client->responsable_paie_id == $user->id) {
                    $client->assignResponsablePaie(null);
                }
                break;
        }

        return redirect()->back()->with('success', 'Client retiré avec succès.');
    }

    public function transferClients(Request $request, User $user)
    {
        $request->validate([
            'new_user_id' => 'required|exists:users,id',
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'role' => 'required|string|in:gestionnaire,binome,responsable',
        ]);

        $newUser = User::find($request->new_user_id);
        foreach ($request->client_ids as $clientId) {
            $client = Client::find($clientId);

            switch ($request->role) {
                case 'gestionnaire':
                    if ($client->gestionnaire_principal_id == $user->id) {
                        $client->assignGestionnairePrincipal($newUser->id);
                    }
                    break;
                case 'binome':
                    if ($client->binome_id == $user->id) {
                        $client->assignBinome($newUser->id);
                    }
                    break;
                case 'responsable':
                    if ($client->responsable_paie_id == $user->id) {
                        $client->assignResponsablePaie($newUser->id);
                    }
                    break;
            }
        }

        return redirect()->back()->with('success', 'Clients transférés avec succès.');
    }

    public function transferClient(Request $request, User $fromUser)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'to_user_id' => 'required|exists:users,id',
            'current_role' => 'required|in:gestionnaire,responsable,binome',
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $client = Client::findOrFail($request->client_id);
            $toUser = User::findOrFail($request->to_user_id);

            // Vérifier si l'utilisateur cible a le bon rôle
            if (!$toUser->hasRole($request->current_role)) {
                throw new \Exception("L'utilisateur sélectionné n'a pas le rôle requis.");
            }

            // Sauvegarder l'ancien état pour l'historique
            $oldState = [
                'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                'responsable_paie_id' => $client->responsable_paie_id,
                'binome_id' => $client->binome_id
            ];

            // Transférer le rôle spécifique
            switch($request->current_role) {
                case 'gestionnaire':
                    $client->gestionnaire_principal_id = $toUser->id;
                    break;
                case 'responsable':
                    $client->responsable_paie_id = $toUser->id;
                    break;
                case 'binome':
                    $client->binome_id = $toUser->id;
                    break;
            }

            $client->save();

            // Créer ou mettre à jour la fiche client
            $ficheClient = FicheClient::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'periode_paie_id' => $request->periode_paie_id
                ],
                [
                    'notes' => $this->formatTransferNotes($request->notes, $fromUser, $toUser, $request->current_role)
                ]
            );

            // Créer un historique du transfert
            ClientHistory::create([
                'client_id' => $client->id,
                'action' => 'transfer',
                'old_state' => json_encode($oldState),
                'new_state' => json_encode([
                    'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                    'responsable_paie_id' => $client->responsable_paie_id,
                    'binome_id' => $client->binome_id
                ]),
                'user_id' => auth()->id(),
                'notes' => "Transfert du rôle {$request->current_role} de {$fromUser->name} à {$toUser->name}"
            ]);

            // Envoyer les notifications
            $toUser->notify(new ClientTransferNotification($client, $request->current_role, $fromUser));
            $fromUser->notify(new ClientTransferNotification($client, $request->current_role, $toUser, true));

            DB::commit();
            return redirect()->back()->with('success', 'Client transféré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function formatTransferNotes(?string $additionalNotes, User $fromUser, User $toUser, string $role): string
    {
        $date = now()->format('d/m/Y H:i');
        $baseNote = "[$date] Transfert du rôle $role de {$fromUser->name} à {$toUser->name}";
        
        if ($additionalNotes) {
            return $baseNote . "\nNotes additionnelles : " . $additionalNotes;
        }
        
        return $baseNote;
    }
}
