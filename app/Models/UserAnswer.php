<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_id',
    ];

    protected function casts(): array
    {
        return [
            'attempt_id' => 'integer',
            'question_id' => 'integer',
            'answer_id' => 'integer',
        ];
    }

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class, 'answer_id');
    }
}