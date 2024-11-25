<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientHistory extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'maj_fiche_para'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}