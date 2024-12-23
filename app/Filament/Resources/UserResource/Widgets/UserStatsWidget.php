<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Admin', User::where('role', 'admin')->count()),
            Stat::make('Editor', User::where('role', 'editor')->count()),
            Stat::make('User', User::where('role', 'user')->count()),
        ];
    }
}
