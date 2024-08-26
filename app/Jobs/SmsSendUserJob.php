<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SmsSendUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private Order $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        Http::post(config('otp.sms.main'), [
            'phone' => $this->order->user->phone,
            'message' => "Your order {$this->order->id} has been {$this->order->status}"
        ]);
    }
}
