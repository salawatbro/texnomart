<?php

namespace App\Services\OTP;

use App\Jobs\SendOTPCodeJob;
use App\Services\BaseService;
use Cache;
use Exception;
use Http;

class SendOTPCode extends BaseService
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
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
        $code = "1909";
        $otp = Cache::get("otp:{$data['phone']}");
        $isSecondary = false;
        if ($otp) {
            $code = "7190";
            $isSecondary = true;
            $attempts = Cache::get("otp:{$data['phone']}:attempts", function () {
                return 0;
            });
            if ($attempts >= config('otp.otp.max_attempts')) {
                throw new Exception('You have reached the maximum number of attempts');
            }
            SendOTPCodeJob::dispatch($data['phone'], $code, true);
        } else {
            SendOTPCodeJob::dispatch($data['phone'], $code, false);
        }
        Cache::set("otp:{$data['phone']}", $code, config('otp.otp.ttl'));
        Cache::increment("otp:{$data['phone']}:attempts");
        return [
            'sent' => true,
            'is_secondary' => $isSecondary,
            'attempts' => Cache::get("otp:{$data['phone']}:attempts"),
        ];
    }
}
