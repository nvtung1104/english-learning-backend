<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'lesson_type_id',
        'video_url',
        'order_number',
    ];

    protected function casts(): array
    {
        return [
            'section_id' => 'integer',
            'lesson_type_id' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function lessonType()
    {
        return $this->belongsTo(LessonType::class, 'lesson_type_id');
    }

    public function materials()
    {
        return $this->hasMany(LessonMaterial::class);
    }

    public function vocabularies()
    {
        return $this->hasMany(Vocabulary::class);
    }

    public function grammarTopics()
    {
        return $this->hasMany(GrammarTopic::class);
    }

    public function listeningLesson()
    {
        return $this->hasOne(ListeningLesson::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function progresses()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function speakingAttempts()
    {
        return $this->hasMany(SpeakingAttempt::class);
    }
}