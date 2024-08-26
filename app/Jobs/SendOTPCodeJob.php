<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendOTPCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private string $phone;
    private string $otpCode;
    private bool $isSecondary;
    public function __construct($phone, $otpCode, $isSecondary = false)
    {
        $this->phone = $phone;
        $this->otpCode = $otpCode;
        $this->isSecondary = $isSecondary;
    }

    public function handle(): void
    {
        if ($this->isSecondary) {
            Http::post(config('otp.sms.secondary'), [
                'phone' => $this->phone,
                'code' => $this->otpCode,
            ]);
        } else {
            Http::post(config('otp.sms.main'), [
                'phone' => $this->phone,
                'code' => $this->otpCode,
            ]);
        }
    }
}
