<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailHystory extends Model
{
    use HasFactory;
    protected $fillable = [
        'mail_id',
        'statut',
    ];

    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
