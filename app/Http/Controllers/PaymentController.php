<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Services\Payment\IndexPaymentTypesService;
use App\Services\Payment\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * @param string $uuid
     * @param PaymentService $service
     * @return PaymentResource|JsonResponse
     */
    public function pay(string $uuid, PaymentService $service)
    {
        try {
            $data = $service->execute($uuid);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return new PaymentResource($data);
    }

    public function indexPaymentTypes(IndexPaymentTypesService $service)
    {
        $data = $service->execute([]);
        return response()->json([
            'data' => $data
        ]);
    }
}
