<?php

namespace App\Http\Requests\RelationsTransferts;

use Illuminate\Foundation\Http\FormRequest;

class TransferGestionnaireClientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajustez selon vos besoins d'autorisation
    }

    public function rules()
    {
        return [
            'new_gestionnaire_id' => 'required|exists:gestionnaires,id|different:gestionnaire_id',
            // 'new_gestionnaire_id' => 'required|exists:users,id',

        ];
    }
}
