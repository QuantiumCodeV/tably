<?php

namespace App\Filament\Widgets;

use App\Models\ActiveSession;
use App\Models\Restaurant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ActiveSessionsChart extends ChartWidget
{
    protected static ?string $heading = 'Активные сессии по часам';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $hours = range(0, 23);
        
        if (!auth()->user()->is_admin) {
            $userRestaurants = Restaurant::where('user_id', Auth::id())->pluck('id')->toArray();
            
            $activeSessions = ActiveSession::whereHas('table', function ($query) use ($userRestaurants) {
                $query->whereIn('restaurant_id', $userRestaurants);
            })
            ->selectRaw('HOUR(started_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->get()
            ->keyBy('hour');
        } else {
            $activeSessions = ActiveSession::selectRaw('HOUR(started_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->get()
                ->keyBy('hour');
        }
        
        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $data[] = $activeSessions[$i]->count ?? 0;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Активные сессии',
                    'data' => $data,
                    'backgroundColor' => '#FF9F40',
                    'borderColor' => '#FF9F40',
                ],
            ],
            'labels' => $hours,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
} 