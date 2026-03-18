<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'lesson_type_id',
        'video_url',
        'order_number',
    ];

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

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}