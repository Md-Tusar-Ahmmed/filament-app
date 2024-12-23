<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardChartWidget2 extends ChartWidget
{
    protected static ?string $heading = 'Post Chart';

    public static ?int $sort = 3;

    protected function getData(): array
    {

        $data = Trend::model(Post::class)
            ->between(
                start: now()->subDays(6),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Posts Chart',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }



    protected function getType(): string
    {
        return 'line';
    }
}
