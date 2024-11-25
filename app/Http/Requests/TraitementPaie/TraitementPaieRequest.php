<?php

namespace App\Http\Requests\TraitementPaie;

use Illuminate\Foundation\Http\FormRequest;

class TraitementPaieRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nbr_bull' => 'required|integer',
            'gestionnaire_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:clients,id',
            'periode_paie_id' => 'required|exists:periodes_paie,id',
            'reception_variable' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (date('Y-m', strtotime($value)) !== date('Y-m')) {
                    $fail('La date de réception des variables doit être dans le mois en cours.');
                }
            }],
            'preparation_bp' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (date('Y-m', strtotime($value)) !== date('Y-m')) {
                    $fail('La date de préparation BP doit être dans le mois en cours.');
                }
            }],
            'validation_bp_client' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (date('Y-m', strtotime($value)) !== date('Y-m')) {
                    $fail('La date de validation BP client doit être dans le mois en cours.');
                }
            }],
            'preparation_envoie_dsn' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (date('Y-m', strtotime($value)) !== date('Y-m')) {
                    $fail('La date de préparation et envoie DSN doit être dans le mois en cours.');
                }
            }],
            'accuses_dsn' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (date('Y-m', strtotime($value)) !== date('Y-m')) {
                    $fail('La date des accusés DSN doit être dans le mois en cours.');
                }
            }],
            'teledec_urssaf' => 'nullable|date',
            'notes' => 'nullable|string',
            'maj_fiche_para_file' => 'nullable|file',
            'reception_variables_file' => 'nullable|file',
            'preparation_bp_file' => 'nullable|file',
            'validation_bp_client_file' => 'nullable|file',
            'preparation_envoi_dsn_file' => 'nullable|file',
            'accuses_dsn_file' => 'nullable|file',
        ];
    }

    public function messages()
    {
        return [
            'nbr_bull.required' => 'Le nombre de bulletins est obligatoire.',
            'nbr_bull.integer' => 'Le nombre de bulletins doit être un entier.',
            'gestionnaire_id.required' => 'Le gestionnaire est obligatoire.',
            'gestionnaire_id.exists' => 'Le gestionnaire sélectionné est invalide.',
            'client_id.required' => 'Le client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné est invalide.',
            'periode_paie_id.required' => 'La période de paie est obligatoire.',
            'periode_paie_id.exists' => 'La période de paie sélectionnée est invalide.',
            'reception_variable.date' => 'La date de réception des variables est invalide.',
            'preparation_bp.date' => 'La date de préparation BP est invalide.',
            'validation_bp_client.date' => 'La date de validation BP client est invalide.',
            'preparation_envoie_dsn.date' => 'La date de préparation et envoi DSN est invalide.',
            'accuses_dsn.date' => 'La date des accusés DSN est invalide.',
            'teledec_urssaf.date' => 'La date de télé-déclaration URSSAF est invalide.',
            'notes.string' => 'Les notes doivent être une chaîne de caractères.',
            'maj_fiche_para_file.file' => 'Le fichier MAJ fiche para doit être un fichier.',
            'reception_variables_file.file' => 'Le fichier de réception des variables doit être un fichier.',
            'preparation_bp_file.file' => 'Le fichier de préparation BP doit être un fichier.',
            'validation_bp_client_file.file' => 'Le fichier de validation BP client doit être un fichier.',
            'preparation_envoi_dsn_file.file' => 'Le fichier de préparation et envoi DSN doit être un fichier.',
            'accuses_dsn_file.file' => 'Le fichier des accusés DSN doit être un fichier.',
        ];
    }
}