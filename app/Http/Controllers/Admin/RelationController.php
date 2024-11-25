<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\RelationCreated;
use App\Notifications\RelationUpdated;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\RelationsTransferts\StoreGestionnaireClientRequest;
use App\Http\Requests\RelationsTransferts\UpdateGestionnaireClientRequest;
use App\Http\Requests\RelationsTransferts\TransferGestionnaireClientRequest;

class RelationController extends Controller
{
    public function filter(Request $request)
    {
        $clients = Client::query();

        if ($request->filled('client_id')) {
            $clients->where('id', $request->client_id);
        }

        if ($request->filled('gestionnaire_id')) {
            $clients->where('gestionnaire_principal_id', $request->gestionnaire_id);
        }

        if ($request->filled('responsable_id')) {
            $clients->where('responsable_paie_id', $request->responsable_id);
        }

        $clients = $clients->with(['gestionnairePrincipal', 'responsablePaie', 'histories'])->get();

        return view('admin.clients.index', compact('clients'));
    }
    /**
     * Affiche la liste des clients avec leurs gestionnaires et responsables.
     */
    public function index()
    {
        // Récupère tous les clients avec leurs relations gestionnaire principal et responsable de paie
        $clients = Client::with(['gestionnairePrincipal', 'responsablePaie'])->get();
    
        // Charge les utilisateurs avec les rôles 'gestionnaire' et 'responsable'
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['gestionnaire', 'responsable']);
        })->get();
    
        // Affiche la vue avec la liste des clients et des utilisateurs
        return view('admin.clients.index', compact('clients', 'users'));
    }

    /**
     * Transfert des clients à un nouveau gestionnaire et/ou responsable.
     */
    public function transfer(Request $request)
{
    // Validation des données envoyées par la requête
    $validated = $request->validate([
        'client_ids' => 'required|array', // Un tableau des IDs des clients
        'client_ids.*' => 'exists:clients,id', // Chaque ID doit exister dans la table clients
        'new_gestionnaire_id' => 'array', // Un tableau des nouveaux gestionnaires par client
        'new_gestionnaire_id.*' => 'nullable|exists:users,id', // Chaque ID de gestionnaire doit exister dans la table des utilisateurs
        'new_responsable_id' => 'array', // Un tableau des nouveaux responsables par client
        'new_responsable_id.*' => 'nullable|exists:users,id', // Chaque ID de responsable doit exister dans la table des utilisateurs
    ]);

    // Démarre une transaction pour assurer que toutes les mises à jour sont effectuées correctement
    DB::beginTransaction();
    try {
        // Parcours des clients pour mettre à jour le gestionnaire et le responsable
        foreach ($validated['client_ids'] as $clientId) {
            $client = Client::findOrFail($clientId);

            // Mise à jour des relations gestionnaire et responsable
            $client->update([
                'gestionnaire_principal_id' => $validated['new_gestionnaire_id'][$clientId] ?? $client->gestionnaire_principal_id,
                'responsable_paie_id' => $validated['new_responsable_id'][$clientId] ?? $client->responsable_paie_id,
            ]);
        }

        // Valide la transaction
        DB::commit();
        return redirect()->route('admin.clients.index')->with('success', 'Clients transférés avec succès.');
    } catch (\Exception $e) {
        // En cas d'erreur, annule toutes les modifications
        DB::rollBack();
        return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    }
}
}
