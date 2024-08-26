<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Services\User\AuthUserService;
use App\Services\User\CheckUserExists;
use App\Services\User\IndexUserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param CheckUserExists $service
     * @return JsonResponse
     */
    public function checkUserExists(Request $request, CheckUserExists $service)
    {
        if ($service->execute($request->phone)) {
            return response()->json(['exists' => true]);
        }
        return response()->json(['exists' => false]);
    }

    /**
     * @param Request $request
     * @param AuthUserService $service
     * @return JsonResponse|JsonResource
     */
    public function auth(Request $request, AuthUserService $service): JsonResponse|JsonResource
    {
        try {
            [$user, $token] = $service->execute($request->all());
        } catch (ValidationException $e) {
            return $this->ValidationErrors($e->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return LoginResource::make($user)->setToken($token);
    }

    /**
     * @param Request $request
     * @return UserResource
     */
    public function getMe(Request $request)
    {
        return UserResource::make($request->user());
    }

    /**
     * @param Request $request
     * @param IndexUserService $service
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request, IndexUserService $service)
    {
        try {
            $users = $service->execute($request->all());
        } catch (ValidationException $e) {
            return $this->ValidationErrors($e->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return UserResource::collection($users);
    }
}
