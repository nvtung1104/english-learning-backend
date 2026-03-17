<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'quiz_id' => 'integer',
            'score' => 'decimal:2',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'attempt_id');
    }
}