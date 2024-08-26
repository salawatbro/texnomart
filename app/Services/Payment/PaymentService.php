<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\BaseService;
use Exception;

class PaymentService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    /**
     * @param string $uuid
     * @return Payment
     * @throws Exception
     */
    public function execute(string $uuid): Payment
    {
        $payment = Payment::where('uuid', $uuid)->first();
        if (!$payment) {
            throw new Exception('Payment not found');
        }
        if ($payment->status === PaymentStatus::Paid) {
            throw new Exception('Payment already paid');
        }
        $payment->status = PaymentStatus::Paid;
        $payment->order()->update([
            'status' => OrderStatus::Processing
        ]);
        $payment->save();
        return $payment;
    }
}
