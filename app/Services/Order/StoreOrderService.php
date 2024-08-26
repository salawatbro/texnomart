<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StoreOrderService extends BaseService
{
    private string $delivery;

    public function rules(): array
    {
        return [
            'delivery' => 'required|int',
            'payment' => 'required|int',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * @param array $data
     * @return Order
     * @throws Exception
     */
    public function execute(array $data): Order
    {
        $this->validate($data);
        $deliveryPrice = $this->calculateDeliveryPrice($data['delivery']);
        $paymentMethod = $this->paymentMethod($data['payment']);
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'price' => 0,
            'delivery' => $this->delivery,
            'payment' => $paymentMethod,
            'status' => OrderStatus::Pending,
        ]);
        $products = $this->getProducts($data['products']);
        $price = $order->price;
        foreach ($data['products'] as $productData) {
            $order_item = OrderItem::where('order_id', $order->id)
                ->where('product_id', $productData['id'])
                ->first();
            if ($order_item) {
                $order_item->quantity += $productData['quantity'];
                $order_item->save();
                continue;
            } else {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productData['id'],
                    'quantity' => $productData['quantity'],
                ]);
            }
            $product = $products->firstWhere('id', $productData['id']);
            $price += $this->calculatePrice($product, $productData['quantity']);
        }
        $order->price = $price + $deliveryPrice;
        $order->save();
        Payment::create([
            'service_id' => $data['payment'],
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'amount' => $order->price,
            'status' => PaymentStatus::Pending,
            'uuid' => (string)Str::uuid(),
        ]);
        return $order->load('items.product')->load('payments');
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
        $this->delivery = $delivery->json('name');
        return $delivery->json('price');
    }

    /**
     * @param int $id
     * @return string
     * @throws Exception
     */
    private function paymentMethod(int $id): string
    {
        $payment = Http::get(config('payment.base_url') . "/$id");
        if ($payment->failed()) {
            throw new Exception('Payment service is not available');
        }
        return $payment->json('name');
    }
}
