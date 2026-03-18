<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    protected $table = 'course_sections';

    protected $fillable = [
        'course_id',
        'title',
        'order_number',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'section_id')->orderBy('order_number');
    }
}