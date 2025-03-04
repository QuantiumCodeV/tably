<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Заказы по дням';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day)->format('Y-m-d');
        });

        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            
            $ordersData = Order::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');
        } else {
            $ordersData = Order::whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get()
                ->keyBy('date');
        }
        
        $data = $days->map(function ($day) use ($ordersData) {
            return $ordersData[$day]->count ?? 0;
        })->toArray();
        
        $labels = $days->map(function ($day) {
            return Carbon::parse($day)->format('d.m');
        })->toArray();
        
        return [
            'datasets' => [
                [
                    'label' => 'Заказы',
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => '#4BC0C0',
                    'tension' => 0.1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
} 