<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Post', Post::count()),
            Stat::make('Published Post', Post::where('published', 1)->count()),
            Stat::make('Unpublished Post', Post::where('published', 0)->count()),
        ];
    }
}
