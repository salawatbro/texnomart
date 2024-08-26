<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\User\AuthUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthUserService $authUserService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
        $this->authUserService = new AuthUserService();
    }

    public function test_it_throws_exception_if_otp_is_expired(): void
    {
        $data = [
            'phone' => '1234567890',
            'otp' => '1234',
        ];

        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn(null);
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('OTP code expired');

        $this->authUserService->execute($data);
    }

    public function test_it_throws_exception_if_otp_is_invalid(): void
    {
        $data = [
            'phone' => '1234567890',
            'otp' => 'wrong-otp',
        ];

        // Mock Cache::get and Cache::forget
        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn('1234');
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid OTP');

        $this->authUserService->execute($data);
    }

    /**
     * @throws Exception
     */
    public function test_it_creates_a_new_user_if_not_exists_and_logs_in(): void
    {
        $data = [
            'phone' => '1234567890',
            'otp' => '1234',
            'name' => 'Test User'
        ];

        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn('1234');
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");

        $this->assertDatabaseMissing('users', ['phone' => $data['phone']]);
        $this->authUserService->execute($data);
        $this->assertDatabaseHas('users', ['phone' => $data['phone'], 'name' => $data['name']]);
    }

    /**
     * @throws Exception
     */
    public function test_it_logs_in_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
        ]);

        $data = [
            'phone' => '1234567890',
            'otp' => '1234',
        ];

        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn('1234');
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");


        $result = $this->authUserService->execute($data);

        $this->assertEquals($user->id, $result[0]->id);
    }

    public function it_throws_exception_if_name_is_missing_for_new_user(): void
    {
        $data = [
            'phone' => '1234567890',
            'otp' => '1234',
        ];

        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn('1234');
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Name is required');

        $this->authUserService->execute($data);
    }

    /**
     * @throws Exception
     */
    public function test_it_returns_existing_user_if_exists(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
        ]);

        $data = [
            'phone' => '1234567890',
            'otp' => '1234',
        ];

        Cache::shouldReceive('get')->with("otp:{$data['phone']}")->andReturn('1234');
        Cache::shouldReceive('forget')->with("otp:{$data['phone']}");

        $result = $this->authUserService->execute($data);

        $this->assertEquals($user->id, $result[0]->id);
    }
}
