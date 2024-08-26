<?php

namespace App\Http\Controllers;

use App\Services\Delivery\IndexDeliveryService;

class DeliveryController extends Controller
{
    public function index(IndexDeliveryService $service)
    {
        $data = $service->execute([]);
        return response()->json([
            'data' => $data
        ]);
    }
}
