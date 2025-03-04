<?php

namespace App\Filament\Widgets;

use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PopularDishesChart extends ChartWidget
{
    protected static ?string $heading = 'Популярные блюда';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            
            $popularDishes = OrderItem::join('menu', 'order_items.menu_item_id', '=', 'menu.id')
                ->whereIn('menu.restaurant_id', $userRestaurants)
                ->select('menu.name', DB::raw('SUM(order_items.quantity) as total_ordered'))
                ->groupBy('menu.name')
                ->orderBy('total_ordered', 'desc')
                ->limit(5)
                ->get();
        } else {
            $popularDishes = OrderItem::join('menu', 'order_items.menu_item_id', '=', 'menu.id')
                ->select('menu.name', DB::raw('SUM(order_items.quantity) as total_ordered'))
                ->groupBy('menu.name')
                ->orderBy('total_ordered', 'desc')
                ->limit(5)
                ->get();
        }
        
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
        
        return [
            'datasets' => [
                [
                    'data' => $popularDishes->pluck('total_ordered')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $popularDishes->count()),
                ],
            ],
            'labels' => $popularDishes->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
} 