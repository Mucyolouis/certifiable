<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ChristiansChart extends ChartWidget
{
    protected static ?string $heading = 'Christian Registration';

    
    // Set a high sort order to ensure it appears first
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::user()->hasAnyRole(['pastor', 'super_admin']);
    }
    protected function getData(): array
    {   
        $data = Trend::model(User::class)
            ->between(
                start: now()->subMonths(3),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Christian Registration',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    
}
