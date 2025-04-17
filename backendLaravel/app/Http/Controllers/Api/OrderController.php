<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\OrderPaymentRequest;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function store(OrderRequest $request)
    {
        $order = $this->orderService->create($request->validated());

        return new OrderResource($order);
    }

    public function getOrder($orderId)
    {
        $order = Order::with('items.menuItem')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }

    public function updateStatus(OrderStatusRequest $request, $orderId)
    {
        $updated = $this->orderService->updateStatus($orderId, $request->validated()['status']);

        return response()->json([
            'order_id' => $updated->id,
            'status' => $updated->status,
            'message' => 'Order status updated successfully'
        ]);
    }

    public function updatePaymentStatus(OrderPaymentRequest $request, $orderId)
    {
        $updated = $this->orderService->updatePaymentStatus($orderId, $request->validated()['payment_status']);

        return response()->json([
            'order_id' => $updated->id,
            'payment_status' => $updated->payment_status,
            'message' => 'Payment status updated successfully'
        ]);
    }
} 