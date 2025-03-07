<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;

class имOrderController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:active_sessions,id',
            'payment_method' => 'required|in:card,cash,sbp',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $sessionId = $request->input('session_id');
        $paymentMethod = $request->input('payment_method');
        $items = $request->input('items');

        // Получаем сессию
        $session = ActiveSession::find($sessionId);

        // Создаем заказ
        $order = Order::create([
            'session_id' => $sessionId,
            'table_id' => $session->table_id,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'total_price' => $request->input('total_price')
        ]);

        // Добавляем позиции заказа
        foreach ($items as $item) {
            $menuItem = Menu::find($item['id']);
            if (!$menuItem) {
                return response()->json(['error' => 'Menu item not found'], 404);
            }
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $menuItem->price
            ]);
        }

        return response()->json([
            'order_id' => $order->id,
            'message' => 'Заказ успешно создан'
        ]);
    }

    public function getOrder($orderId)
    {
        $order = Order::with('items.menuItem')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,preparing,ready,delivered,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = $request->input('status');
        $order->save();

        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'message' => 'Order status updated successfully'
        ]);
    }

    public function updatePaymentStatus(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->payment_status = $request->input('payment_status');
        $order->save();

        // Если заказ оплачен, обновляем статус сессии
        if ($order->payment_status === 'paid') {
            $session = ActiveSession::find($order->session_id);
            if ($session) {
                $session->status = 'payed';
                $session->save();
            }
        }

        return response()->json([
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'message' => 'Payment status updated successfully'
        ]);
    }
} 