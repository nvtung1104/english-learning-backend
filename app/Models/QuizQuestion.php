<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'parent_id',
        'question',
        'title',
        'passage',
        'question_type',
        'image_url',
        'audio_url',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'quiz_id'   => 'integer',
            'parent_id' => 'integer',
            'meta'      => 'array',
        ];
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    public function subQuestions()
    {
        return $this->hasMany(QuizQuestion::class, 'parent_id')->with('answers');
    }

    public function parent()
    {
        return $this->belongsTo(QuizQuestion::class, 'parent_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}