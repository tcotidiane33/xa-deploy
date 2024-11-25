<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePaieHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode_paie_id',
        'user_id',
        'action',
        'details',
    ];

    public function periodePaie()
    {
        return $this->belongsTo(PeriodePaie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}