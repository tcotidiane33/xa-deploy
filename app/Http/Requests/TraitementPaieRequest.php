<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraitementPaieRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     */
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'gestionnaire_id' => 'required|exists:users,id',
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            'nbr_bull' => 'required|integer',
            'teledec_urssaf' => 'nullable|date',
            'reception_variables' => 'nullable|date',
            'preparation_bp' => 'nullable|date',
            'validation_bp_client' => 'nullable|date',
            'preparation_envoie_dsn' => 'nullable|date',
            'accuses_dsn' => 'nullable|date',
            'notes' => 'nullable|string',
            'maj_fiche_para_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'reception_variables_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'preparation_bp_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'validation_bp_client_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'preparation_envoi_dsn_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'accuses_dsn_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
        ];
    }
}