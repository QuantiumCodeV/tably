<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'table_id' => $this->table_id,
            'status' => $this->status,
            'session_token' => $this->session_token,
            'started_at' => $this->started_at,
            'expires_at' => $this->expires_at,
        ];
    }
}
