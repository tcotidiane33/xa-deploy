<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'traitement_paie_id',
        'type',
        'path',
    ];

    public function traitementPaie()
    {
        return $this->belongsTo(TraitementPaie::class);
    }
}
