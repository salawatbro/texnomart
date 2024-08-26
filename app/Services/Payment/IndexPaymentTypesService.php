<?php

namespace App\Services\Payment;

use App\Services\BaseService;
use Illuminate\Support\Facades\Http;

class IndexPaymentTypesService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function execute(array $data): array
    {
        $types = Http::get(config('payment.base_url'));
        return $types->json();
    }
}
