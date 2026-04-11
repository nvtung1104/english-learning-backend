<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'lesson_id'       => $this->lesson_id,
            'title'           => $this->title,
            'time_limit'      => $this->time_limit,
            'pass_score'      => $this->pass_score,
            'questions_count' => $this->questions_count ?? $this->whenLoaded('questions', fn() => $this->questions->count()),
            'questions'       => QuizQuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}