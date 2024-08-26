<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'uuid' => $this->uuid,
            'url'=> route('payment', ['uuid' => $this->uuid]),
            'order' => new OrderResource($this->whenLoaded('order')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
