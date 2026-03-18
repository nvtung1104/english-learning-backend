<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpeakingAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'lesson_id'  => $this->lesson_id,
            'audio_url'  => $this->audio_url,
            'score'      => $this->score,
            'feedback'   => $this->feedback,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
