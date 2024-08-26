<?php

namespace App\Services\Delivery;

use App\Services\BaseService;
use Illuminate\Support\Facades\Http;

class IndexDeliveryService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function execute(array $data): array
    {
        $types = Http::get(config('delivery.delivery.url'));
        return $types->json();
    }
}
