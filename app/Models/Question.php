<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'content',
        'status',
        'participant_data',
        'event_id'
    ];
    // Pertenece a un evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    // Tiene una respuesta
    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
