<?php

namespace App\Http\Requests\RelationsTransferts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGestionnaireClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
        'gestionnaire_id' => 'required|exists:gestionnaires,id',
        'is_principal' => 'boolean',
        'gestionnaires_secondaires' => 'nullable|array',
        'gestionnaires_secondaires.*' => 'exists:gestionnaires,id',
        'user_id' => 'required|exists:users,id',
        'notes' => 'nullable|string',
        ];
    }
}
