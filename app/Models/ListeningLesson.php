<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListeningLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'audio_url',
        'transcript',
    ];

    protected function casts(): array
    {
        return [
            'lesson_id' => 'integer',
        ];
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}