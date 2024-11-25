<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;


    protected $fillable = ['post_id', 'filename', 'path', 'mime_type', 'size'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
