<?php

namespace App\Filament\Widgets;

use App\Services\MarriagePredictionService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MarriagePredictionStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $predictionService = new MarriagePredictionService();
        $predictions = $predictionService->predictFutureMarriages(3);
        $metrics = $predictionService->getAccuracyMetrics();

        $stats = [];

        if (!empty($predictions['predictions'])) {
            foreach ($predictions['predictions'] as $prediction) {
                $stats[] = Stat::make(
                    "Predicted Marriages for " . $prediction['date'],
                    $prediction['predicted_marriages']
                )
                ->description("Range: {$prediction['lower_bound']} - {$prediction['upper_bound']}")
                ->color('success');
            }
        }

        if (!$metrics['error']) {
            $stats[] = Stat::make(
                'Model Accuracy',
                $metrics['accuracy_percentage'] . '%'
            )
            ->description('Based on historical data')
            ->color('info');
        }

        return $stats;
    }
}