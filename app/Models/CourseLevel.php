<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLevel extends Model
{
    protected $table = 'course_levels';

    protected $fillable = [
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'level_id');
    }
}