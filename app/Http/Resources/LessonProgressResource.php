<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'lesson_id'    => $this->lesson_id,
            'status'       => $this->status,
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            'user'         => new UserResource($this->whenLoaded('user')),
            'lesson'       => new LessonResource($this->whenLoaded('lesson')),
        ];
    }
}
