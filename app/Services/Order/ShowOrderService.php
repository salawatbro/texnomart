<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\BaseService;
use Exception;

class ShowOrderService extends BaseService
{
    /**
     * @param Order $order
     * @return Order
     * @throws Exception
     */
    public function execute(Order $order): Order
    {
        if (!auth()->user()->hasRole('admin') and $order->user_id !== auth()->id()) {
           throw new Exception('You do not have permission to view this order');
        }
        $order->load('items', 'payments');
        if (auth()->user()->hasRole('admin')) {
            $order->load('user');
        }
        return $order;
    }
}
