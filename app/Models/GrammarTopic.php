<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrammarTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'explanation',
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