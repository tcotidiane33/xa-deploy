<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;



class Notification extends BaseNotification
{
    use HasFactory;

    protected $fillable = [
        'type', 'notifiable', 'data', 'read_at', 'notifiable_type', 'message', 'notifiable_id'
    ];
    protected $keyType = 'string';
    public $incrementing = false;
    
    public function notifiable()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
