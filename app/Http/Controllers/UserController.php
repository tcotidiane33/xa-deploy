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
        return view('users.index', compact('users', 'posts','tickets'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
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

        // Récupérer tous les clients pour permettre l'ajout de nouveaux clients
        $clients = Client::all();
        $users = User::all();

        return view('users.show', compact('user', 'clients','users'));
    }


    public function edit(User $user)
    {
        $roles = Role::all();
        $clients = Client::all();
        $users = User::all(); // Ajoutez cette ligne pour récupérer tous les utilisateurs
        return view('users.edit', compact('user', 'roles', 'clients', 'users'));
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
        $clients = Client::whereHas('gestionnaires', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->paginate(15);

        return view('users.manage_clients', compact('user', 'clients'));
    }

    public function attachClient(Request $request, User $user)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'role' => 'required|string|in:gestionnaire,binome,responsable',
        ]);

        $client = Client::find($request->client_id);

        switch ($request->role) {
            case 'gestionnaire':
                $client->assignGestionnairePrincipal($user->id);
                break;
            case 'binome':
                $client->assignBinome($user->id);
                break;
            case 'responsable':
                $client->assignResponsablePaie($user->id);
                break;
        }

        return redirect()->back()->with('success', 'Client ajouté avec succès.');
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

    private function transferClientBetweenUsers(Client $client, User $fromUser, User $toUser)
    {
        $client->gestionnaires()->detach($fromUser->id);
        $client->gestionnaires()->attach($toUser->id);

        if ($client->gestionnaire_principal_id == $fromUser->id) {
            $client->gestionnaire_principal_id = $toUser->id;
            $client->save();
        }
    }
}
