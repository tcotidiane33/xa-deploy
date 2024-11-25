<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeriodePaie extends Model
{
    use HasFactory;
    protected $table = 'periodes_paie';

    protected $fillable = [
        'reference', 'debut', 'fin', 'validee'
    ];

    protected $dates = ['debut', 'fin'];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'validee' => 'boolean',
    ];

    public function fichesClients()
    {
        return $this->hasMany(FicheClient::class);
    }

    public function generateReference()
    {
        $this->reference = 'PERIODE_' . strtoupper($this->debut->format('F_Y'));
    }

    public static function getNonCloturees()
    {
        return self::where('validee', false)->get();
    }
    public function progressPercentage()
    {
        // Implémentez la logique pour calculer le pourcentage de progression
        $totalSteps = 10; // Nombre total d'étapes (incluant les fichiers)
        $completedSteps = 0;
        $fichesClientsCount = $this->fichesClients->count();

        if ($fichesClientsCount === 0) {
            return 0;
        }

        foreach ($this->fichesClients as $ficheClient) {
            if ($ficheClient->reception_variables) $completedSteps++;
            if ($ficheClient->reception_variables_file) $completedSteps++;
            if ($ficheClient->preparation_bp) $completedSteps++;
            if ($ficheClient->preparation_bp_file) $completedSteps++;
            if ($ficheClient->validation_bp_client) $completedSteps++;
            if ($ficheClient->validation_bp_client_file) $completedSteps++;
            if ($ficheClient->preparation_envoie_dsn) $completedSteps++;
            if ($ficheClient->preparation_envoie_dsn_file) $completedSteps++;
            if ($ficheClient->accuses_dsn) $completedSteps++;
            if ($ficheClient->accuses_dsn_file) $completedSteps++;
        }

        return ($completedSteps / ($totalSteps * $fichesClientsCount)) * 100;
    }
}