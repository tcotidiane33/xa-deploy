<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\User;
use App\Models\ConventionCollective;

class ClientForm extends Component
{
    public $currentStep = 1;
    public $totalSteps = 4;
    public $client_id;
    public $name, $type_societe, $ville, $dirigeant_nom, $dirigeant_telephone, $dirigeant_email;
    public $contact_paie_nom, $contact_paie_prenom, $contact_paie_telephone, $contact_paie_email;
    public $contact_compta_nom, $contact_compta_prenom, $contact_compta_telephone, $contact_compta_email;
    public $responsable_paie_id, $responsable_telephone_ld, $gestionnaire_principal_id, $binome_id, $convention_collective_id, $maj_fiche_para;
    public $saisie_variables, $date_saisie_variables, $client_forme_saisie, $date_formation_saisie, $date_debut_prestation, $date_fin_prestation, $date_signature_contrat, $date_rappel_mail, $taux_at, $adhesion_mydrh, $date_adhesion_mydrh, $is_cabinet = false, $portfolio_cabinet_id;

    public function mount($clientId = null)
    {
        // Initialisation des valeurs booléennes
        $this->saisie_variables = false;
        $this->client_forme_saisie = false;
        $this->adhesion_mydrh = false;
        $this->is_cabinet = false;

        if ($clientId) {
            $this->loadClient($clientId);
        }
    }

    public function loadClient($clientId)
    {
        $client = Client::findOrFail($clientId);
        $this->client_id = $client->id;
        $this->name = $client->name;
        $this->type_societe = $client->type_societe;
        $this->ville = $client->ville;
        $this->dirigeant_nom = $client->dirigeant_nom;
        $this->dirigeant_telephone = $client->dirigeant_telephone;
        $this->dirigeant_email = $client->dirigeant_email;
        $this->contact_paie_nom = $client->contact_paie_nom;
        $this->contact_paie_prenom = $client->contact_paie_prenom;
        $this->contact_paie_telephone = $client->contact_paie_telephone;
        $this->contact_paie_email = $client->contact_paie_email;
        $this->contact_compta_nom = $client->contact_compta_nom;
        $this->contact_compta_prenom = $client->contact_compta_prenom;
        $this->contact_compta_telephone = $client->contact_compta_telephone;
        $this->contact_compta_email = $client->contact_compta_email;
        $this->responsable_paie_id = $client->responsable_paie_id;
        $this->responsable_telephone_ld = $client->responsable_telephone_ld;
        $this->gestionnaire_principal_id = $client->gestionnaire_principal_id;
        $this->binome_id = $client->binome_id;
        $this->convention_collective_id = $client->convention_collective_id;
        $this->maj_fiche_para = $client->maj_fiche_para;
        $this->saisie_variables = $client->saisie_variables;
        $this->date_saisie_variables = $client->date_saisie_variables;
        $this->client_forme_saisie = $client->client_forme_saisie;
        $this->date_formation_saisie = $client->date_formation_saisie;
        $this->date_debut_prestation = $client->date_debut_prestation;
        $this->date_fin_prestation = $client->date_fin_prestation;
        $this->date_signature_contrat = $client->date_signature_contrat;
        $this->date_rappel_mail = $client->date_rappel_mail;
        $this->taux_at = $client->taux_at;
        $this->adhesion_mydrh = $client->adhesion_mydrh;
        $this->date_adhesion_mydrh = $client->date_adhesion_mydrh;
        $this->is_cabinet = $client->is_cabinet;
        $this->portfolio_cabinet_id = $client->portfolio_cabinet_id;
    }

    public function render()
    {
        $responsables = User::whereHas('roles', function ($query) {
            $query->where('name', 'responsable');
        })->get();

        $gestionnaires = User::whereHas('roles', function ($query) {
            $query->where('name', 'gestionnaire');
        })->get();

        $conventions = ConventionCollective::all();
        $clients = Client::all();

        return view('livewire.client-form', compact('responsables', 'gestionnaires', 'conventions', 'clients'));
    }

    public function nextStep()
    {
        $this->validateStep();
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function submitForm()
    {
        $this->validateStep();

        // Enregistrer les données du formulaire
        $client = Client::updateOrCreate(
            ['id' => $this->client_id],
            [
                'name' => $this->name,
                'type_societe' => $this->type_societe,
                'ville' => $this->ville,
                'dirigeant_nom' => $this->dirigeant_nom,
                'dirigeant_telephone' => $this->dirigeant_telephone,
                'dirigeant_email' => $this->dirigeant_email,
                'contact_paie_nom' => $this->contact_paie_nom,
                'contact_paie_prenom' => $this->contact_paie_prenom,
                'contact_paie_telephone' => $this->contact_paie_telephone,
                'contact_paie_email' => $this->contact_paie_email,
                'contact_compta_nom' => $this->contact_compta_nom,
                'contact_compta_prenom' => $this->contact_compta_prenom,
                'contact_compta_telephone' => $this->contact_compta_telephone,
                'contact_compta_email' => $this->contact_compta_email,
                'responsable_paie_id' => $this->responsable_paie_id,
                'responsable_telephone_ld' => $this->responsable_telephone_ld,
                'gestionnaire_principal_id' => $this->gestionnaire_principal_id,
                'binome_id' => $this->binome_id,
                'convention_collective_id' => $this->convention_collective_id,
                'maj_fiche_para' => $this->maj_fiche_para,
                // 'saisie_variables' => $this->saisie_variables,
                'saisie_variables' => $this->saisie_variables ?? false,

                'date_saisie_variables' => $this->date_saisie_variables,
                // 'client_forme_saisie' => $this->client_forme_saisie,
                'client_forme_saisie' => $this->client_forme_saisie ?? false,

                'date_formation_saisie' => $this->date_formation_saisie,
                'date_debut_prestation' => $this->date_debut_prestation,
                'date_fin_prestation' => $this->date_fin_prestation,
                'date_signature_contrat' => $this->date_signature_contrat,
                'date_rappel_mail' => $this->date_rappel_mail,
                'taux_at' => $this->taux_at,
                // 'adhesion_mydrh' => $this->adhesion_mydrh,
                'adhesion_mydrh' => $this->adhesion_mydrh ?? false,

                'date_adhesion_mydrh' => $this->date_adhesion_mydrh,
                // 'is_cabinet' => $this->is_cabinet,
                'is_cabinet' => $this->is_cabinet ?? false,

                'portfolio_cabinet_id' => $this->portfolio_cabinet_id,
            ]
        );

        $client = Client::updateOrCreate(
            ['id' => $this->client_id],
            $this->except('client_id')
        );

        session()->flash('message', 'Client enregistré avec succès.');

        return redirect()->route('clients.index');
    }

    private function validateStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'name' => 'required|string|max:255',
                    'type_societe' => 'nullable|string|max:255',
                    'ville' => 'nullable|string|max:255',
                    'dirigeant_nom' => 'nullable|string|max:255',
                    'dirigeant_telephone' => 'nullable|string|max:255',
                    'dirigeant_email' => 'nullable|email|max:255',
                ];
                break;
            case 2:
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
            case 3:
                $rules = [
                    'responsable_paie_id' => 'required|exists:users,id',
                    'responsable_telephone_ld' => 'nullable|string|max:255',
                    'gestionnaire_principal_id' => 'required|exists:users,id',
                    'binome_id' => 'required|exists:users,id',
                    'convention_collective_id' => 'nullable|exists:convention_collective,id',
                    'maj_fiche_para' => 'nullable|date',
                ];
                break;
            case 4:
                $rules = [
                    'saisie_variables' => 'nullable|boolean',
                    'date_saisie_variables' => 'nullable|date',
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

        $this->validate($rules);
    }
}
