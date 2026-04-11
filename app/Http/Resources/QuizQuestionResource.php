<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'quiz_id'       => $this->quiz_id,
            'question'      => $this->question,
            'title'         => $this->title,
            'passage'       => $this->passage,
            'question_type' => $this->question_type,
            'image_url'     => $this->image_url,
            'audio_url'     => $this->audio_url,
            'meta'          => $this->meta,
            'answers'       => QuizAnswerResource::collection($this->whenLoaded('answers')),
            'questions'     => QuizQuestionResource::collection($this->whenLoaded('subQuestions')),
        ];
    }
}