<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFicheClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ou utilisez une logique d'autorisation appropriée
    }

    public function rules()
    {
        return [
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            'client_id' => 'required|exists:clients,id',
            'reception_variables' => 'nullable|date',
            'reception_variables_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'preparation_bp' => 'nullable|date',
            'preparation_bp_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'validation_bp_client' => 'nullable|date',
            'validation_bp_client_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'preparation_envoie_dsn' => 'nullable|date',
            'preparation_envoie_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'accuses_dsn' => 'nullable|date',
            'accuses_dsn_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nb_bulletins_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'maj_fiche_para_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ];
    }
}