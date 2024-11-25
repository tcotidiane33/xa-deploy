<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConventionCollective extends Model
{
    protected $table = 'convention_collective';

    protected $fillable = ['idcc', 'name', 'description'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}