<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PeriodePaie;

class StoreFicheClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ou utilisez une logique d'autorisation appropriée
    }

    public function rules()
    {
        return [
            'client_id' => [
                'required',
                'exists:clients,id',
                Rule::unique('fiches_clients')->where(function ($query) {
                    return $query->where('client_id', $this->client_id)
                        ->where('periode_paie_id', $this->periode_paie_id);
                }),
            ],
            'periode_paie_id' => [
                'required',
                'exists:periodes_paie,id',
                function ($attribute, $value, $fail) {
                    $periodePaie = PeriodePaie::find($value);
                    if ($periodePaie && $periodePaie->validee) {
                        $fail('La période de paie sélectionnée est clôturée.');
                    }
                },
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
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client sélectionné est invalide.',
            'periode_paie_id.required' => 'La période de paie est requise.',
            'periode_paie_id.exists' => 'La période de paie sélectionnée est invalide.',
            'reception_variables.date' => 'La date de réception des variables est invalide.',
            'preparation_bp.date' => 'La date de préparation BP est invalide.',
            'validation_bp_client.date' => 'La date de validation BP client est invalide.',
            'preparation_envoie_dsn.date' => 'La date de préparation et envoi DSN est invalide.',
            'accuses_dsn.date' => 'La date des accusés DSN est invalide.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.'
        ];
    }
}
