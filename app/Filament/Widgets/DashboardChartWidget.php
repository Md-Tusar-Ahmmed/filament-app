<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DashboardChartWidget extends ChartWidget
{
    protected static ?string $heading = 'User Chart';

    public static ?int $sort = 3;

    protected function getData(): array
    {

        $data = Trend::model(User::class)
            ->between(
                start: now()->subMonth(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Users Chart',
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
