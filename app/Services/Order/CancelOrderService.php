<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\BaseService;
use Exception;

class CancelOrderService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    /**
     * @param Order $order
     * @return void
     * @throws Exception
     */
    public function execute(Order $order): void
    {
        if ($order->user_id !== auth()->user()->id) {
            throw new Exception('You can only cancel your own orders');
        }
        if ($order->status !== OrderStatus::Pending) {
            throw new Exception('Only pending orders can be canceled');
        }
        $order->status = OrderStatus::Cancelled;
        $order->save();
    }
}
