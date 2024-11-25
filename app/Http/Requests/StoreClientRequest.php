<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'responsable_paie_id' => 'nullable|exists:users,id',
            'gestionnaire_principal_id' => 'nullable|exists:users,id',
            'gestionnaires_secondaires' => 'nullable|array',
            'gestionnaires_secondaires.*' => 'exists:users,id',
            'date_debut_prestation' => 'nullable|date',
            'date_estimative_envoi_variables' => 'nullable|date',
            'date_rappel_mail' => 'nullable|date',
            'contact_paie' => 'nullable|string|max:255',
            'contact_comptabilite' => 'nullable|string|max:255',
            'status' => 'required|string|in:actif,inactif',
            'nb_bulletins' => 'nullable|integer|min:0',
            'maj_fiche_para' => 'nullable|date',
            'convention_collective_id' => 'nullable|exists:convention_collective,id',
            'is_portfolio' => 'nullable|boolean',
            'parent_client_id' => 'nullable|exists:clients,id',
            'type_societe' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'dirigeant_nom' => 'nullable|string|max:255',
            'dirigeant_telephone' => 'nullable|string|max:20',
            'dirigeant_email' => 'nullable|email|max:255',
            'contact_paie_nom' => 'nullable|string|max:255',
            'contact_paie_prenom' => 'nullable|string|max:255',
            'contact_paie_telephone' => 'nullable|string|max:20',
            'contact_paie_email' => 'nullable|email|max:255',
            'contact_compta_nom' => 'nullable|string|max:255',
            'contact_compta_prenom' => 'nullable|string|max:255',
            'contact_compta_telephone' => 'nullable|string|max:20',
            'contact_compta_email' => 'nullable|email|max:255',
            'binome_id' => 'nullable|exists:users,id',
            'responsable_telephone_ld' => 'nullable|string|max:20',
            'gestionnaire_telephone_ld' => 'nullable|string|max:20',
            'binome_telephone_ld' => 'nullable|string|max:20',
            'saisie_variables' => 'nullable|boolean',
            'client_forme_saisie' => 'nullable|boolean',
            'date_formation_saisie' => 'nullable|date',
            'date_fin_prestation' => 'nullable|date',
            'date_signature_contrat' => 'nullable|date',
            'taux_at' => 'nullable|string|max:255',
            'adhesion_mydrh' => 'nullable|boolean',
            'date_adhesion_mydrh' => 'nullable|date',
            'is_cabinet' => 'nullable|boolean',
            'portfolio_cabinet_id' => 'nullable|exists:clients,id',
        ];
    }
}