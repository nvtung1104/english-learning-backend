<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'level_id',
        'price',
        'thumbnail',
        'duration',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration' => 'integer',
            'status' => 'integer',
            'category_id' => 'integer',
            'level_id' => 'integer',
            'created_by' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function level()
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}