<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Client;
use App\Models\FicheClient;
use App\Models\PeriodePaie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\RelationCreated;
use App\Notifications\RelationUpdated;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\RelationsTransferts\StoreGestionnaireClientRequest;
use App\Http\Requests\RelationsTransferts\UpdateGestionnaireClientRequest;
use App\Http\Requests\RelationsTransferts\TransferGestionnaireClientRequest;
use App\Services\ClientService;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ClientTransferNotification;

class RelationController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function filter(Request $request)
    {
        // Récupérer les responsables et gestionnaires pour les filtres
        $responsables = User::role('responsable')->get();
        $gestionnaires = User::role('gestionnaire')->get();

        // Construire la requête de base
        $query = Client::query()
            ->with(['gestionnairePrincipal', 'responsablePaie'])
            ->select('clients.*'); // Assurez-vous de sélectionner tous les champs nécessaires

        // Appliquer les filtres
        if ($request->filled('responsable_id')) {
            $query->where('responsable_paie_id', $request->responsable_id);
        }

        if ($request->filled('gestionnaire_id')) {
            $query->where('gestionnaire_principal_id', $request->gestionnaire_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Récupérer les résultats paginés
        $relations = $query->latest()->paginate(10);

        // Récupérer les périodes de paie
        $periodesPaie = PeriodePaie::orderBy('debut', 'desc')->get();

        return view('admin.client_user.index', compact(
            'relations',
            'responsables',
            'gestionnaires',
            'periodesPaie'
        ));
    }

    /**
     * Affiche la liste des clients avec leurs gestionnaires et responsables.
     */
    public function index()
    {
        // Récupérer les responsables (utilisateurs avec le rôle 'responsable')
        $responsables = User::role('responsable')->get();

        // Récupérer les gestionnaires (utilisateurs avec le rôle 'gestionnaire')
        $gestionnaires = User::role('gestionnaire')->get();

        // Récupérer les clients avec leurs relations
        $relations = Client::with(['gestionnairePrincipal', 'responsablePaie'])
            ->select('clients.*') // Assurez-vous de sélectionner tous les champs nécessaires
            ->latest()
            ->paginate(10);

        // Récupérer les périodes de paie
        $periodesPaie = PeriodePaie::orderBy('debut', 'desc')->get();

        return view('admin.client_user.index', compact(
            'relations',
            'responsables',
            'gestionnaires',
            'periodesPaie'
        ));
    }

    /**
     * Transfert des clients à un nouveau gestionnaire et/ou responsable.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'new_gestionnaire_id' => 'nullable|exists:users,id',
            'new_responsable_id' => 'nullable|exists:users,id',
            'new_binome_id' => 'nullable|exists:users,id',
            'periode_paie_id' => 'nullable|exists:periodes_paie,id'
        ]);

        try {
            DB::beginTransaction();

            $clientIds = json_decode($request->client_ids);
            $clients = Client::whereIn('id', $clientIds)->get();
            
            // Collecter tous les utilisateurs à notifier
            $usersToNotify = collect();
            
            foreach ($clients as $client) {
                $oldState = [
                    'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                    'responsable_paie_id' => $client->responsable_paie_id,
                    'binome_id' => $client->binome_id
                ];

                // Stocker les anciens utilisateurs pour les notifications
                if ($client->gestionnairePrincipal) $usersToNotify->push($client->gestionnairePrincipal);
                if ($client->responsablePaie) $usersToNotify->push($client->responsablePaie);
                if ($client->binome) $usersToNotify->push($client->binome);

                // Mise à jour des relations
                $updates = [];
                if ($request->filled('new_gestionnaire_id')) {
                    $updates['gestionnaire_principal_id'] = $request->new_gestionnaire_id;
                    $usersToNotify->push(User::find($request->new_gestionnaire_id));
                }
                if ($request->filled('new_responsable_id')) {
                    $updates['responsable_paie_id'] = $request->new_responsable_id;
                    $usersToNotify->push(User::find($request->new_responsable_id));
                }
                if ($request->filled('new_binome_id')) {
                    $updates['binome_id'] = $request->new_binome_id;
                    $usersToNotify->push(User::find($request->new_binome_id));
                }

                $client->update($updates);

                // Créer ou mettre à jour la fiche client
                if ($request->filled('periode_paie_id')) {
                    FicheClient::updateOrCreate(
                        [
                            'client_id' => $client->id,
                            'periode_paie_id' => $request->periode_paie_id
                        ],
                        [
                            'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                            'responsable_paie_id' => $client->responsable_paie_id,
                            'binome_id' => $client->binome_id,
                            'notes' => $this->formatTransferNotes($oldState, $client)
                        ]
                    );
                }

                // Créer un historique du transfert
                $client->histories()->create([
                    'action' => 'transfer',
                    'old_state' => json_encode($oldState),
                    'new_state' => json_encode([
                        'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                        'responsable_paie_id' => $client->responsable_paie_id,
                        'binome_id' => $client->binome_id
                    ]),
                    'user_id' => auth()->id(),
                    'periode_paie_id' => $request->periode_paie_id
                ]);
            }

            // Envoyer les notifications aux utilisateurs uniques
            $usersToNotify = $usersToNotify->unique('id');
            
            Notification::send($usersToNotify, new ClientTransferNotification([
                'clients' => $clients->pluck('name')->toArray(),
                'action' => 'transfer',
                'transferred_by' => auth()->user()->name,
                'transferred_at' => now()
            ]));

            DB::commit();
            return redirect()->route('admin.client_user.index')
                            ->with('success', 'Les transferts ont été effectués avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Une erreur est survenue lors du transfert : ' . $e->getMessage());
        }
    }

    private function formatTransferNotes($oldState, $client)
    {
        $changes = [];
        $date = now()->format('d/m/Y H:i');

        if ($oldState['gestionnaire_principal_id'] != $client->gestionnaire_principal_id) {
            $oldGestionnaire = User::find($oldState['gestionnaire_principal_id']);
            $newGestionnaire = User::find($client->gestionnaire_principal_id);
            $changes[] = "Gestionnaire principal : de " . 
                ($oldGestionnaire ? $oldGestionnaire->name : 'Non assigné') . 
                " à " . 
                ($newGestionnaire ? $newGestionnaire->name : 'Non assigné');
        }

        if ($oldState['responsable_paie_id'] != $client->responsable_paie_id) {
            $oldResponsable = User::find($oldState['responsable_paie_id']);
            $newResponsable = User::find($client->responsable_paie_id);
            $changes[] = "Responsable paie : de " . 
                ($oldResponsable ? $oldResponsable->name : 'Non assigné') . 
                " à " . 
                ($newResponsable ? $newResponsable->name : 'Non assigné');
        }

        if ($oldState['binome_id'] != $client->binome_id) {
            $oldBinome = User::find($oldState['binome_id']);
            $newBinome = User::find($client->binome_id);
            $changes[] = "Binôme : de " . 
                ($oldBinome ? $oldBinome->name : 'Non assigné') . 
                " à " . 
                ($newBinome ? $newBinome->name : 'Non assigné');
        }

        return "[$date] " . implode(" | ", $changes);
    }

    /**
     * Affiche le formulaire de modification d'une relation client/utilisateur.
     */
    public function edit($id)
    {
        // Récupérer le client avec ses relations et sa fiche client courante
        $client = Client::with([
            'gestionnairePrincipal',
            'responsablePaie',
            'binome',
            'fichesClients' => function($query) {
                $query->latest();
            }
        ])->findOrFail($id);

        // Récupérer les responsables avec leurs informations de contact
        $responsables = User::role('responsable')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Récupérer les gestionnaires avec leurs informations de contact
        $gestionnaires = User::role('gestionnaire')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Récupérer les périodes de paie actives
        $periodesPaie = PeriodePaie::where('fin', '>=', now())
            ->orderBy('debut', 'desc')
            ->get();

        return view('admin.client_user.edit', compact(
            'client',
            'responsables',
            'gestionnaires',
            'periodesPaie'
        ));
    }

    /**
     * Met à jour la relation client/utilisateur.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'responsable_paie_id' => 'nullable|exists:users,id',
            'gestionnaire_principal_id' => 'nullable|exists:users,id',
            'binome_id' => 'nullable|exists:users,id',
            'periode_paie_id' => 'nullable|exists:periodes_paie,id'
        ]);

        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);
            $oldState = [
                'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                'responsable_paie_id' => $client->responsable_paie_id,
                'binome_id' => $client->binome_id
            ];

            // Mise à jour des relations
            $client->update([
                'responsable_paie_id' => $request->responsable_paie_id,
                'gestionnaire_principal_id' => $request->gestionnaire_principal_id,
                'binome_id' => $request->binome_id
            ]);

            // Créer ou mettre à jour la fiche client seulement si une période est sélectionnée
            if ($request->filled('periode_paie_id')) {
                FicheClient::updateOrCreate(
                    [
                        'client_id' => $id,
                        'periode_paie_id' => $request->periode_paie_id
                    ],
                    [
                        'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                        'responsable_paie_id' => $client->responsable_paie_id,
                        'binome_id' => $client->binome_id,
                        'notes' => $this->formatTransferNotes($oldState, $client)
                    ]
                );
            }

            // Créer un historique de la modification
            $client->histories()->create([
                'action' => 'update',
                'old_state' => json_encode($oldState),
                'new_state' => json_encode([
                    'gestionnaire_principal_id' => $client->gestionnaire_principal_id,
                    'responsable_paie_id' => $client->responsable_paie_id,
                    'binome_id' => $client->binome_id
                ]),
                'user_id' => auth()->id(),
                'periode_paie_id' => $request->periode_paie_id
            ]);

            DB::commit();
            return redirect()->route('admin.client_user.index')
                            ->with('success', 'La relation a été mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
