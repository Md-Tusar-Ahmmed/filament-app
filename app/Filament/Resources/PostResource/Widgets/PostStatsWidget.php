<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Post;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsWidget extends BaseWidget
{

    use InteractsWithPageTable;
 
    protected function getTablePage(): string
    {
        return ListPosts::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Post', $this->getPageTableQuery()->count()),
            Stat::make('Published Post', $this->getPageTableQuery()->where('published', 1)->count()),
            Stat::make('Unpublished Post', $this->getPageTableQuery()->where('published', 0)->count()),
        ];
    }
}
