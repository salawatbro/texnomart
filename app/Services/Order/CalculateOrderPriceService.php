<?php

namespace App\Services\Order;

use App\Models\Product;
use App\Services\BaseService;
use Exception;
use Http;
use Illuminate\Database\Eloquent\Collection;

class CalculateOrderPriceService extends BaseService
{
    public function rules(): array
    {
        return [
            'delivery' => 'required|int',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function execute(array $data): array
    {
        $this->validate($data);
        $products = $this->getProducts($data['products']);
        $price_by_products = [];
        $price = 0;
        foreach ($data['products'] as $productData) {
            $product = $products->firstWhere('id', $productData['id']);
            $price += $this->calculatePrice($product, $productData['quantity']);
            $price_by_products[] = [
                'product' => $product,
                'quantity' => $productData['quantity'],
                'price' => $this->calculatePrice($product, $productData['quantity']),
            ];
        }
        $deliveryPrice = $this->calculateDeliveryPrice($data['delivery']);
        return [
            'products' => $price_by_products,
            'delivery' => $deliveryPrice,
            'price' => $price + $deliveryPrice,
        ];
    }

    /**
     * @param array $products
     * @return Collection
     */
    private function getProducts(array $products): Collection
    {
        return Product::whereIn('id', array_column($products, 'id'))->get();
    }

    /**
     * @param Product $product
     * @param $quantity
     * @return float
     */
    private function calculatePrice(Product $product, $quantity): float
    {
        return $product->price * $quantity;
    }

    /**
     * @param int $id
     * @return float
     * @throws Exception
     */
    private function calculateDeliveryPrice(int $id): float
    {
        $delivery = Http::get(config('delivery.delivery.url') . "/$id");
        if ($delivery->failed()) {
            throw new Exception('Delivery service is not available');
        }
        return $delivery->json('price');
    }
}
