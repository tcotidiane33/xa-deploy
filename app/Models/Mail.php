<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $fillable = [
        'expediteur_id',
        'recepteur_id',
        'content',
        'statut',
    ];

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    public function recepteur()
    {
        return $this->belongsTo(User::class, 'recepteur_id');
    }
}
