<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListeningLessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'lesson_id'  => $this->lesson_id,
            'audio_url'  => $this->audio_url,
            'transcript' => $this->transcript,
        ];
    }
}
