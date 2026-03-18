<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'quiz_id'     => $this->quiz_id,
            'score'       => $this->score,
            'started_at'  => $this->started_at?->format('Y-m-d H:i:s'),
            'finished_at' => $this->finished_at?->format('Y-m-d H:i:s'),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
