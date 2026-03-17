<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'question_id' => 'integer',
            'is_correct' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'answer_id');
    }
}