<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidget extends BaseWidget
{

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('New users that have joined')
                ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
                ->chart([1, 10, 5, 15, 10, 20])
                ->color('success'),
            stat::make('Total Category', Category::count())
                ->description('New Category Added')
                ->descriptionIcon('heroicon-o-folder', IconPosition::Before)
                ->chart([1, 10, 20, 15, 30, 20, 10, 30, 20])
                ->color('success'),
            Stat::make('Total Post', Post::count())
                ->description('New post created')
                ->descriptionIcon('heroicon-o-rectangle-stack', IconPosition::Before)
                ->chart([1, 15, 20, 15, 10, 20])
                ->color('success'),
        ];
    }
}
