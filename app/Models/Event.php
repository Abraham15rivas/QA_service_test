<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
       'title',
       'slug',
       'description',
       'image',
       'video',
       'qa',
       'status',
       'user_id'
    ];
    // Puede tener muchas preguntas
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
