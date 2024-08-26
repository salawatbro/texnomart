<?php

namespace App\Http\Controllers;

use App\Services\OTP\SendOTPCode;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OTPController extends Controller
{
    /**
     * @param Request $request
     * @param SendOTPCode $service
     * @return JsonResponse
     */
    public function sendOTPCode(Request $request, SendOTPCode $service)
    {
        try {
            $data = $service->execute($request->all());
            return response()->json($data);
        } catch (ValidationException $e) {
            return $this->ValidationErrors($e->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
    }
}
