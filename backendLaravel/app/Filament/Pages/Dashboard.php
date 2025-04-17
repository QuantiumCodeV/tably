<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActiveSessionsChart;
use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\PopularDishesChart;
use App\Filament\Widgets\RevenueChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Панель управления';
    protected static ?string $title = 'Панель управления';

    // Переопределяем метод getWidgets, чтобы указать только нужные виджеты
    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
            OrdersChart::class,
            RevenueChart::class,
            PopularDishesChart::class,
            ActiveSessionsChart::class,
        ];
    }
    
    // Удаляем или комментируем метод getHeaderWidgets
    // protected function getHeaderWidgets(): array
    // {
    //     return [];
    // }
    
    // Удаляем или комментируем метод getFooterWidgets, если он есть
    // protected function getFooterWidgets(): array
    // {
    //     return [];
    // }
}