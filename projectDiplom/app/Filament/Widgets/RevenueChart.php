<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Выручка по дням';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            
            $revenueData = Order::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })
            ->whereBetween('orders.created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price * order_items.quantity) as total')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        } else {
            $revenueData = Order::whereBetween('orders.created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
                ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price * order_items.quantity) as total')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->groupBy('date')
                ->get()
                ->keyBy('date');
        }
        
        $data = $days->map(function ($day) use ($revenueData) {
            return $revenueData[$day]->total ?? 0;
        })->toArray();
        
        $labels = $days->map(function ($day) {
            return Carbon::parse($day)->format('d.m');
        })->toArray();
        
        return [
            'datasets' => [
                [
                    'label' => 'Выручка (₽)',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
} 