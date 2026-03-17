<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order_number',
    ];

    protected function casts(): array
    {
        return [
            'course_id' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'section_id')->orderBy('order_number');
    }
}