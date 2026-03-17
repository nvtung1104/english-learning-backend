<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpeakingAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'audio_url',
        'score',
        'feedback',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'lesson_id' => 'integer',
            'score' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}