<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'delivery' => $this->delivery,
            'status' => $this->status,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payment' => PaymentResource::collection($this->whenLoaded('payments')),
            'user'=> new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
