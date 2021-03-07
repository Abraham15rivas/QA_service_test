<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'content',
        'status',
        'question_id'
    ];
    // Pertenece a una pregunta
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
