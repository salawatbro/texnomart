<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;

abstract class Controller
{
    public function ResponseError($message, $code = 400)
    {
        return response()->json(['error' => $message, 'errors' => []], $code);
    }

    public function ValidationErrors(Validator $validator)
    {
        return response()->json(['error' => 'Validation error', 'errors' => $validator->errors()->all()], 400);
    }
}
