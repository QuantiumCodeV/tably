<?php

namespace App\Filament\Widgets;

use App\Models\ActiveSession;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Table;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            
            $restaurantsCount = count($userRestaurants);
            
            $tablesCount = Table::whereIn('restaurant_id', $userRestaurants)->count();
            
            $ordersCount = Order::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })->count();
            
            $totalRevenue = Order::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })->join('order_items', 'orders.id', '=', 'order_items.order_id')
              ->sum(DB::raw('order_items.price * order_items.quantity'));
            
            $activeSessionsCount = ActiveSession::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })->count();
        } else {
            $restaurantsCount = Restaurant::count();
            $tablesCount = Table::count();
            $ordersCount = Order::count();
            $totalRevenue = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
              ->sum(DB::raw('order_items.price * order_items.quantity'));
            $activeSessionsCount = ActiveSession::count();
        }
        
        return [
            Stat::make('Рестораны', $restaurantsCount)
                ->description('Общее количество ресторанов')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),
                
            Stat::make('Столы', $tablesCount)
                ->description('Общее количество столов')
                ->descriptionIcon('heroicon-m-table-cells')
                ->color('success'),
                
            Stat::make('Заказы', $ordersCount)
                ->description('Общее количество заказов')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
                
            Stat::make('Выручка', number_format($totalRevenue, 2) . ' ₽')
                ->description('Общая выручка')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Активные сессии', $activeSessionsCount)
                ->description('Текущие активные сессии')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),
        ];
    }
} 