<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\BaseService;

class DeleteOrderService extends BaseService
{
    /**
     * @param Order $order
     * @return Order
     */
    public function execute(Order $order): Order
    {
        $order->items()->delete();
        $order->payments()->delete();
        $order->delete();

        return $order;
    }
}
