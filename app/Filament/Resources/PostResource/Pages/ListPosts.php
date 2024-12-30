<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\PostResource\Widgets\PostStatsWidget;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListPosts extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = PostResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];

        

        
    }

    

    protected function getHeaderWidgets(): array
    {
        return [
            PostStatsWidget::class,
        ];
    }

    
}
