<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFicheClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ou utilisez une logique d'autorisation appropriée
    }

    public function rules()
    {
        return [
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            // 'client_id' => 'required|exists:clients,id',
            'client_id' => [
                'required',
                'exists:clients,id',
                Rule::unique('fiches_clients')
                    ->where(function ($query) {
                        return $query->where('client_id', $this->client_id)
                                    ->where('periode_paie_id', $this->periode_paie_id);
                    })
            ],
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
            'notes' => 'nullable|string',
        ];
    }

    
    public function messages()
    {
        return [
            'client_id.unique' => 'Une fiche existe déjà pour ce client sur cette période de paie.',
            // ... autres messages ...
        ];
    }
}