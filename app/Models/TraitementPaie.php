<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class TraitementPaie extends Model
{
    protected $table = 'traitements_paie';

    protected $fillable = [
        'reference',
        'gestionnaire_id',
        'client_id',
        'periode_paie_id',
        'teledec_urssaf',
        'est_verrouille'
    ];

    protected $dates = [
        'teledec_urssaf'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($traitementPaie) {
            $traitementPaie->reference = 'TP-' . Str::upper(Str::random(8));
        });
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // public function periodePaie()
    // {
    //     return $this->belongsTo(PeriodePaie::class);
    // }

    public function periodePaie()
    {
        return $this->belongsTo(PeriodePaie::class, 'periode_paie_id');
    }



    // public function getNotesAttribute($value)
    // {
    //     $previousNotes = FicheClient::where('client_id', $this->client_id)
    //         ->where('periode_paie_id', '<', $this->periode_paie_id)
    //         ->pluck('notes')
    //         ->implode("\n");

    //     return $previousNotes . "\n" . $value;
    // }

    public function getNotesAttribute($value)
    {
        $previousNotes = FicheClient::where('client_id', $this->client_id)
            ->where('periode_paie_id', '<', $this->periode_paie_id)
            ->orderBy('periode_paie_id', 'desc')
            ->get()
            ->map(function ($fiche) {
                return $fiche->created_at->format('Y-m-d') . ': ' . $fiche->notes;
            })
            ->implode("\n");

        return $previousNotes . "\n" . $value;
    }

    public function ficheClient()
    {
        return $this->belongsTo(FicheClient::class, 'client_id', 'client_id')
            ->where('periode_paie_id', $this->periode_paie_id);
    }
}
