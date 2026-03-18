<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'avatar'            => $this->avatar,
            'status'            => $this->status,
            'email_verified_at' => $this->email_verified_at?->format('Y-m-d H:i:s'),
            'last_login'        => $this->last_login?->format('Y-m-d H:i:s'),
            'created_at'        => $this->created_at?->format('Y-m-d H:i:s'),
            'roles'             => $this->whenLoaded('roles', fn() =>
                $this->roles->pluck('name')
            ),
        ];
    }
}
