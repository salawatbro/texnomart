<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculateOrderPriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'price_by_products' => $this->priceByProducts(),
            'delivery_price' => $this->resource['delivery'],
            'price' => $this->resource['price'],
        ];
    }

    private function priceByProducts(): array
    {
        $price_by_products = [];
        foreach ($this->resource['products'] as $productData) {
            $price_by_products[] = [
                'product' => ProductResource::make($productData['product']),
                'quantity' => $productData['quantity'],
                'price' => $productData['price'],
            ];
        }
        return $price_by_products;
    }
}
