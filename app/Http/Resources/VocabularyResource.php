<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VocabularyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'lesson_id' => $this->lesson_id,
            'word'      => $this->word,
            'meaning'   => $this->meaning,
            'example'   => $this->example,
            'audio'     => $this->audio,
        ];
    }
}
