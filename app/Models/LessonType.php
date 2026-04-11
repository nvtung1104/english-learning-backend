<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'description', 'category', 'sub_topics',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'lesson_type_id');
    }
}