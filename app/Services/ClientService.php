<?php
namespace App\Services;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;

class ClientService
{
    public function getClients(Request $request)
{
    $query = Client::query();

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    if ($request->has('is_cabinet') && $request->is_cabinet != '') {
        $query->where('is_cabinet', $request->is_cabinet);
    }

    if ($request->has('portfolio_cabinet_id') && $request->portfolio_cabinet_id != '') {
        $query->where('portfolio_cabinet_id', $request->portfolio_cabinet_id);
    }

    if ($request->has('adhesion_mydrh') && $request->adhesion_mydrh != '') {
        $query->where('adhesion_mydrh', $request->adhesion_mydrh);
    }

    if ($request->has('client_forme_saisie') && $request->client_forme_saisie != '') {
        $query->where('client_forme_saisie', $request->client_forme_saisie);
    }

    return $query;
}

    public function getClientGrowthData()
    {
        // Implémentez la logique pour obtenir les données de croissance des clients
        return Client::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();
    }

    public function getTopConventionsData()
    {
        // Implémentez la logique pour obtenir les données de croissance des clients
        return Client::selectRaw('convention_collective_id, COUNT(*) as count')
            ->groupBy('convention_collective_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
    }

    public function getClientsByManagerData()
    {
        // Implémentez la logique pour obtenir les clients par gestionnaire
        return Client::selectRaw('gestionnaire_principal_id, COUNT(*) as count')
            ->groupBy('gestionnaire_principal_id')
            ->orderBy('count', 'desc')
            ->get();
    }
    public function storeClient(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name',
            'email' => 'required|email|max:255|unique:clients,email',
            'phone' => 'nullable|string|max:255',
            'portfolio_cabinet_id' => 'nullable|exists:clients,id',
            // Ajoutez d'autres règles de validation ici
        ]);

        // Créer un nouveau client
        $client = Client::create($validatedData);

        return $client;
    }

    public function updateClient(Request $request, $id)
    {
        // Valider les données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name,' . $id,
            'email' => 'required|email|max:255|unique:clients,email,' . $id,
            'phone' => 'nullable|string|max:255',
            'is_cabinet' => 'nullable|boolean',
            'portfolio_cabinet_id' => 'nullable|exists:clients,id',
            // Ajoutez d'autres règles de validation ici
        ]);

        // Mettre à jour le client
        $client = Client::findOrFail($id);
        $client->update($validatedData);

        return $client;
    }

    public function deleteClient(Client $client)
    {
        // Supprimer le client
        $client->delete();

        return response()->json(['success' => true]);
    }

    public function storePartial(Request $request)
    {
        Log::info('Début de la méthode storePartial');
        $step = $request->input('step');
        Log::info('Étape actuelle : ' . $step);

        // Définir les règles de validation en fonction de l'étape
        $rules = $this->getValidationRules($step, $request->input('client_id'));

        // Valider les données de la requête
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::info('Validation échouée pour l\'étape : ' . $step, ['errors' => $validator->errors()]);
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Enregistrer ou mettre à jour les données partiellement validées
        $validatedData = $validator->validated();

        $client = Client::updateOrCreate(
            ['id' => $request->input('client_id')],
            $validatedData
        );

        Log::info('Données enregistrées pour l\'étape : ' . $step, ['client_id' => $client->id]);

        // Déterminer l'étape suivante
        $nextStep = $this->getNextStep($step);

        return [
            'success' => true,
            'nextStep' => $nextStep,
            'client_id' => $client->id,
        ];
    }
    private function getValidationRules($step, $clientId = null)
    {
        $rules = [];

        switch ($step) {
            case 'societe':
                $rules = [
                    'reference' => 'required|string|max:255',
                    'name' => 'required|string|max:255|unique:clients,name,' . ($clientId ?? 'NULL'),
                    'type_societe' => 'nullable|string|max:255',
                    'ville' => 'nullable|string|max:255',
                    'dirigeant_nom' => 'nullable|string|max:255',
                    'dirigeant_telephone' => 'nullable|string|max:255',
                    'dirigeant_email' => 'nullable|email|max:255',
                ];
                break;
            case 'contacts':
                $rules = [
                    'contact_paie_nom' => 'nullable|string|max:255',
                    'contact_paie_prenom' => 'nullable|string|max:255',
                    'contact_paie_telephone' => 'nullable|string|max:255',
                    'contact_paie_email' => 'nullable|email|max:255',
                    'contact_compta_nom' => 'nullable|string|max:255',
                    'contact_compta_prenom' => 'nullable|string|max:255',
                    'contact_compta_telephone' => 'nullable|string|max:255',
                    'contact_compta_email' => 'nullable|email|max:255',
                ];
                break;
            case 'interne':
                $rules = [
                    'responsable_paie_id' => 'required|exists:users,id',
                    'responsable_telephone_ld' => 'nullable|string|max:255',
                    'gestionnaire_principal_id' => 'required|exists:users,id',
                    'binome_id' => 'required|exists:users,id',
                    'gestionnaire_telephone_ld' => 'nullable|string|max:255',
                    'binome_telephone_ld' => 'nullable|string|max:255',
                    'convention_collective_id' => 'nullable|exists:convention_collective,id',
                    'maj_fiche_para' => 'nullable|date',
                ];
                break;
            case 'supplementaires':
                $rules = [
                    'saisie_variables' => 'nullable|boolean',
                    'client_forme_saisie' => 'nullable|boolean',
                    'date_formation_saisie' => 'nullable|date',
                    'date_debut_prestation' => 'nullable|date',
                    'date_fin_prestation' => 'nullable|date',
                    'date_signature_contrat' => 'nullable|date',
                    'date_rappel_mail' => 'nullable|date',
                    'taux_at' => 'required|string|max:255',
                    'adhesion_mydrh' => 'nullable|boolean',
                    'date_adhesion_mydrh' => 'nullable|date',
                    'is_cabinet' => 'nullable|boolean',
                    'portfolio_cabinet_id' => 'nullable|exists:clients,id',
                ];
                break;
        }
        return $rules;
    }

    private function getNextStep($currentStep)
    {
        $steps = ['societe', 'contacts', 'interne', 'supplementaires'];
        $currentIndex = array_search($currentStep, $steps);
        return $currentIndex !== false && $currentIndex < count($steps) - 1 ? $steps[$currentIndex + 1] : null;
    }


    public function getClientInfo(Client $client)
    {
        // Récupérer les informations du client
        return $client;
    }

    public function transferClients(Request $request)
    {
        // Implémentez la logique pour transférer des clients
        $validatedData = $request->validate([
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
            'new_manager_id' => 'required|exists:users,id',
        ]);

        Client::whereIn('id', $validatedData['client_ids'])
            ->update(['gestionnaire_principal_id' => $validatedData['new_manager_id']]);

        return response()->json(['success' => true]);
    }

    public function validateStep(Request $request, $step)
    {
        // Implémentez la logique pour valider une étape
        $rules = [];
        switch ($step) {
            case 'societe':
                $rules = [
                    'name' => 'required|string|max:255',
                    'type_societe' => 'nullable|string|max:255',
                    'ville' => 'nullable|string|max:255',
                    'dirigeant_nom' => 'nullable|string|max:255',
                    'dirigeant_telephone' => 'nullable|string|max:255',
                    'dirigeant_email' => 'nullable|email|max:255',
                    'date_estimative_envoi_variables' => 'nullable|date',
                    'nb_bulletins' => 'nullable|integer',
                    'reference' => 'nullable|string|max:255',
                ];
                break;
            case 'contacts':
                $rules = [
                    'contact_paie_nom' => 'nullable|string|max:255',
                    'contact_paie_prenom' => 'nullable|string|max:255',
                    'contact_paie_telephone' => 'nullable|string|max:255',
                    'contact_paie_email' => 'nullable|email|max:255',
                    'contact_compta_nom' => 'nullable|string|max:255',
                    'contact_compta_prenom' => 'nullable|string|max:255',
                    'contact_compta_telephone' => 'nullable|string|max:255',
                    'contact_compta_email' => 'nullable|email|max:255',
                ];
                break;
            case 'interne':
                $rules = [
                    'responsable_paie_id' => 'required|exists:users,id',
                    'responsable_telephone_ld' => 'nullable|string|max:255',
                    'gestionnaire_principal_id' => 'required|exists:users,id',
                    'binome_id' => 'required|exists:users,id',
                    'gestionnaire_telephone_ld' => 'nullable|string|max:255',
                    'binome_telephone_ld' => 'nullable|string|max:255',
                    'convention_collective_id' => 'nullable|exists:convention_collectives,id',
                    'maj_fiche_para' => 'nullable|date',
                ];
                break;
            case 'supplementaires':
                $rules = [
                    'saisie_variables' => 'nullable|boolean',
                    'client_forme_saisie' => 'nullable|boolean',
                    'date_formation_saisie' => 'nullable|date',
                    'date_debut_prestation' => 'nullable|date',
                    'date_fin_prestation' => 'nullable|date',
                    'date_signature_contrat' => 'nullable|date',
                    'date_rappel_mail' => 'nullable|date',
                    'taux_at' => 'required|string|max:255',
                    'adhesion_mydrh' => 'nullable|boolean',
                    'date_adhesion_mydrh' => 'nullable|date',
                    'is_cabinet' => 'nullable|boolean',
                    'portfolio_cabinet_id' => 'nullable|exists:clients,id',
                ];
                break;
        }

        return Validator::make($request->all(), $rules);
    }

    public function updatePartial(Request $request, Client $client, $step)
    {
        // Valider les données en fonction de l'étape
        $validator = $this->validateStep($request, $step);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // Mettre à jour partiellement le client
        $client->update($validatedData);

        return $client;
    }

    public function updateRelation(Request $request, $userId)
    {
        // Implémentez la logique pour mettre à jour une relation
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'relation_type' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($userId);
        $client = Client::findOrFail($validatedData['client_id']);

        // Mettre à jour la relation en fonction du type
        if ($validatedData['relation_type'] === 'gestionnaire') {
            $client->gestionnaire_principal_id = $user->id;
        } elseif ($validatedData['relation_type'] === 'binome') {
            $client->binome_id = $user->id;
        }

        $client->save();

        return $client;
    }

    public function attachGestionnaire(Request $request, Client $client)
    {
        // Implémentez la logique pour attacher un gestionnaire à un client
        $validatedData = $request->validate([
            'gestionnaire_id' => 'required|exists:users,id',
        ]);

        $client->gestionnaire_principal_id = $validatedData['gestionnaire_id'];
        $client->save();

        return $client;
    }

    public function detachGestionnaire(Request $request, Client $client)
    {
        // Implémentez la logique pour détacher un gestionnaire d'un client
        $client->gestionnaire_principal_id = null;
        $client->save();

        return $client;
    }

    public function getClientEvents(Client $client)
    {
        // Implémentez la logique pour obtenir les événements d'un client
        return $client->events;
    }

    public function exportClients()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }
}
