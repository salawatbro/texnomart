<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Jobs\SmsSendUserJob;
use App\Models\Order;
use App\Services\BaseService;
use Http;
use Illuminate\Validation\Rule;

class ChangeStatusOrderService extends BaseService
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(OrderStatus::class)],
        ];
    }

    /**
     * @param Order $order
     * @param array $data
     * @return Order
     */
    public function execute(Order $order, array $data): Order
    {
        $this->validate($data);
        $order->load('user');
        $order->status = $data['status'];
        $order->save();
        $this->sendSMS($order);
        return $order;
    }

    /**
     * @param Order $order
     * @return void
     */
    public function sendSMS(Order $order): void
    {
       SmsSendUserJob::dispatch($order);
    }
}
