<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculateOrderPriceResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Order\calculateOrderPriceService;
use App\Services\Order\CancelOrderService;
use App\Services\Order\ChangeStatusOrderService;
use App\Services\Order\DeleteOrderService;
use App\Services\Order\HistoryOrderService;
use App\Services\Order\IndexOrderService;
use App\Services\Order\ShowOrderService;
use App\Services\Order\StoreOrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{

    /**
     * Calculate the order price.
     *
     * @param Request $request
     * @param \App\Services\Order\CalculateOrderPriceService $service
     * @return JsonResponse|CalculateOrderPriceResource
     */
    public function calculateOrderPrice(Request $request, CalculateOrderPriceService $service)
    {
        try {
            $data = $service->execute($request->all());
        } catch (ValidationException $exception) {
            return $this->ValidationErrors($exception->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return new CalculateOrderPriceResource($data);
    }

    /**
     * Store a new order.
     *
     * @param Request $request
     * @param StoreOrderService $service
     * @return JsonResponse|OrderResource
     */
    public function store(Request $request, StoreOrderService $service)
    {
        try {
            $data = $service->execute($request->all());
        } catch (ValidationException $exception) {
            return $this->ValidationErrors($exception->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return new OrderResource($data);
    }

    /**
     * Cancel an order.
     *
     * @param Order $order
     * @param CancelOrderService $service
     * @return JsonResponse
     */
    public function cancel(Order $order, CancelOrderService $service)
    {
        try {
            $service->execute($order);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return response()->json([
            'data' => [
                'message' => 'Order canceled'
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param IndexOrderService $service
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request, IndexOrderService $service)
    {
        try {
            $data = $service->execute($request->all());
        } catch (ValidationException $exception) {
            return $this->ValidationErrors($exception->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return OrderResource::collection($data);
    }

    /**
     * @param Request $request
     * @param HistoryOrderService $service
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function history(Request $request, HistoryOrderService $service)
    {
        try {
            $data = $service->execute($request->all());
        } catch (ValidationException $exception) {
            return $this->ValidationErrors($exception->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return OrderResource::collection($data);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @param ChangeStatusOrderService $service
     * @return OrderResource|JsonResponse
     */
    public function changeStatus(Request $request, Order $order, ChangeStatusOrderService $service)
    {
        try {
            $data = $service->execute($order, $request->all());
        } catch (ValidationException $exception) {
            return $this->ValidationErrors($exception->validator);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return new OrderResource($data);
    }

    /**
     * @param Order $order
     * @param DeleteOrderService $service
     * @return JsonResponse
     */
    public function destroy(Order $order, DeleteOrderService $service)
    {
        try {
            $service->execute($order);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return response()->json([
            'data' => [
                'message' => 'Order deleted'
            ]
        ]);
    }

    /**
     * @param Order $order
     * @param ShowOrderService $service
     * @return OrderResource|JsonResponse
     */
    public function show(Order $order, ShowOrderService $service)
    {
        try {
            $order = $service->execute($order);
        } catch (Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
        return new OrderResource($order);
    }
}
