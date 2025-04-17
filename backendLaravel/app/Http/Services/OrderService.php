<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\ActiveSession;

class OrderService
{

    public function create(array $data): Order
    {
        $session = ActiveSession::findOrFail($data['session_id']);

        $order = Order::create([
            'session_id' => $data['session_id'],
            'table_id' => $session->table_id,
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'total_price' => $data['total_price'],
        ]);

        foreach ($data['items'] as $item) {
            $menu = Menu::findOrFail($item['id']);

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
            ]);
        }

        return $order->fresh('items');
    }

    public function updateStatus(int $orderId, string $status): Order
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        return $order;
    }

    public function updatePaymentStatus(int $orderId, string $paymentStatus): Order
    {
        $order = Order::findOrFail($orderId);
        $order->payment_status = $paymentStatus;
        $order->save();

        if ($paymentStatus === 'paid') {
            $session = ActiveSession::find($order->session_id);
            if ($session) {
                $session->status = 'payed';
                $session->save();
            }
        }

        return $order;
    }
}
