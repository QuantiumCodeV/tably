<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'payment_method' => $this->payment_method,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'items' => $this->items->map(fn($item) => [
                'menu_id' => $item->menu_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]),
        ];
    }
}
