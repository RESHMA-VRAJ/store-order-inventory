<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerOrderRequest;
use App\Jobs\SendOrderConfirmation;
use App\Services\CustomerOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class CustomerOrderController extends Controller
{

public function store(
    StoreCustomerOrderRequest $request,
    CustomerOrderService $orderService
): JsonResponse {
    $validated = $request->validated();

    try {
        $order = $orderService->createOrder(
            (int) $validated['customer_id'],
            $validated['items']
        );

        // Dispatch only after successful order creation.
        SendOrderConfirmation::dispatch($order->id);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'data' => $order,
        ], 201);

    } catch (ValidationException $e) {
        throw $e;

    } catch (Throwable $e) {
        report($e);

        return response()->json([
            'success' => false,
            'message' => 'Unable to create the order.',
        ], 500);
    }
}

}