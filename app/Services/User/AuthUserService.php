<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Cache;

class AuthUserService extends BaseService
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
            'name' => 'nullable|string',
            'otp' => 'required|string',
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
        $otp = Cache::get("otp:{$data['phone']}");
        if (!$otp) {
            Cache::forget("otp:{$data['phone']}");
            throw new Exception('OTP code expired');
        }
        if ($otp !== $data['otp']) {
            throw new Exception('Invalid OTP');
        }
        $user = User::where('phone', $data['phone'])->first();
        if (!$user) {
            if (!isset($data['name'])) {
                throw new Exception('Name is required');
            }
            $user = User::create([
                'phone' => $data['phone'],
                'name' => $data['name']
            ]);
            $user->assignRole('user');
        }
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $token = auth('api')->login($user);
        return [$user, $token];
    }
}
