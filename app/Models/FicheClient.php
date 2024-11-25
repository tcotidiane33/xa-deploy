<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;


class FicheClient extends Model
{
    use HasFactory;
    protected $table = 'fiches_clients';

    protected $fillable = [
        'periode_paie_id', 'client_id', 'reception_variables', 'reception_variables_file', 'preparation_bp',
        'preparation_bp_file', 'validation_bp_client', 'validation_bp_client_file', 'preparation_envoie_dsn',
        'preparation_envoie_dsn_file', 'accuses_dsn', 'accuses_dsn_file', 'notes', 'nb_bulletins_file',
        'maj_fiche_para_file'
    ];

    protected $dates = [
        'reception_variables', 'preparation_bp', 'validation_bp_client',
        'preparation_envoie_dsn', 'accuses_dsn'
    ];

    public function periodePaie()
    {
        return $this->belongsTo(PeriodePaie::class);
    }

    
    public function traitementPaie()
    {
        return $this->hasOne(TraitementPaie::class, 'client_id', 'client_id')
                    ->where('periode_paie_id', $this->periode_paie_id);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function progressPercentage()
    {
        $totalSteps = 5; // Nombre total d'étapes
        $completedSteps = 0;

        if ($this->reception_variables) $completedSteps++;
        if ($this->preparation_bp) $completedSteps++;
        if ($this->validation_bp_client) $completedSteps++;
        if ($this->preparation_envoie_dsn) $completedSteps++;
        if ($this->accuses_dsn) $completedSteps++;

        return ($completedSteps / $totalSteps) * 100;
    }

    public function getReceptionVariablesAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getPreparationBpAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getValidationBpClientAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getPreparationEnvoieDsnAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getAccusesDsnAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}