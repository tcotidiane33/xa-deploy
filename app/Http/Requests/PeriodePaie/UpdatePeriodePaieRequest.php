<?php

namespace App\Http\Requests\PeriodePaie;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeriodePaieRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ou utilisez une logique d'autorisation appropriée
    }

    public function rules()
    {
        $periodePaieId = $this->periodePaie(); // Utilisez la méthode pour obtenir l'ID de la période de paie

        return [
            'reference' => 'required|string|max:255|unique:periodes_paie,reference,' . $periodePaieId,
            'debut' => 'required|date',
            'fin' => 'required|date|after:debut',
            'validee' => 'boolean',
            'client_id' => 'required|exists:clients,id',
            'reception_variables' => 'nullable|date|after_or_equal:debut|before_or_equal:fin',
            'preparation_bp' => 'nullable|date|after_or_equal:reception_variables|before_or_equal:fin',
            'validation_bp_client' => 'nullable|date|after_or_equal:preparation_bp|before_or_equal:fin',
            'preparation_envoie_dsn' => 'nullable|date|after_or_equal:validation_bp_client|before_or_equal:fin',
            'accuses_dsn' => 'nullable|date|after_or_equal:preparation_envoie_dsn|before_or_equal:fin',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get the ID of the PeriodePaie from the route.
     *
     * @return int|null
     */
    public function periodePaie()
    {
        return $this->route('periodePaie') ? $this->route('periodePaie')->id : null;
    }
}