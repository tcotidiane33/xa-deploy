<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'description', 'statut', 'priorite', 'createur_id', 'assigne_a_id'
    ];

    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }

    public function assigneA()
    {
        return $this->belongsTo(User::class, 'assigne_a_id');
    }

    public function commentaires()
    {
        return $this->hasMany(CommentaireTicket::class);
    }
}
