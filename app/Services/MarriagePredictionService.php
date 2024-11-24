<?php

namespace App\Services;

use App\Models\Marriage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use Illuminate\Support\Collection;

class MarriagePredictionService
{
    public function getHistoricalData(): Collection
    {
        // Get monthly marriage counts from the past
        return Marriage::select(
            DB::raw('DATE_FORMAT(marriage_date, "%Y-%m-01") as date'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    protected function prepareTrainingData(Collection $historicalData): array
    {
        $samples = [];
        $targets = [];
        
        // Convert dates to numeric values (months since start)
        $startDate = Carbon::parse($historicalData->first()->date);
        
        foreach ($historicalData as $index => $data) {
            $currentDate = Carbon::parse($data->date);
            $monthsDiff = $startDate->diffInMonths($currentDate);
            
            // Add seasonal features (month of year)
            $month = $currentDate->month;
            $samples[] = [
                $monthsDiff, // Time trend
                sin(2 * pi() * $month / 12), // Seasonal component (sine)
                cos(2 * pi() * $month / 12), // Seasonal component (cosine)
            ];
            
            $targets[] = $data->count;
        }
        
        return [$samples, $targets];
    }

    public function predictFutureMarriages(int $months = 6): array
    {
        try {
            $historicalData = $this->getHistoricalData();
            
            if ($historicalData->isEmpty()) {
                return [
                    'predictions' => [],
                    'error' => 'Insufficient historical data for prediction'
                ];
            }

            // Prepare training data
            [$samples, $targets] = $this->prepareTrainingData($historicalData);
            
            // Train the model
            $regression = new LeastSquares();
            $regression->train($samples, $targets);
            
            // Generate predictions
            $predictions = [];
            $lastDate = Carbon::parse($historicalData->last()->date);
            
            for ($i = 1; $i <= $months; $i++) {
                $predictionDate = $lastDate->copy()->addMonths($i);
                $monthsDiff = Carbon::parse($historicalData->first()->date)
                    ->diffInMonths($predictionDate);
                
                $month = $predictionDate->month;
                $prediction = $regression->predict([
                    $monthsDiff,
                    sin(2 * pi() * $month / 12),
                    cos(2 * pi() * $month / 12),
                ]);
                
                // Calculate confidence interval (using standard error)
                $standardError = $this->calculateStandardError($targets);
                $confidenceInterval = 1.96 * $standardError; // 95% confidence interval
                
                $predictions[] = [
                    'date' => $predictionDate->format('Y-m'),
                    'predicted_marriages' => round(max(0, $prediction)), // Ensure non-negative
                    'lower_bound' => round(max(0, $prediction - $confidenceInterval)),
                    'upper_bound' => round($prediction + $confidenceInterval)
                ];
            }

            return [
                'predictions' => $predictions,
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'predictions' => [],
                'error' => 'Error generating prediction: ' . $e->getMessage()
            ];
        }
    }

    protected function calculateStandardError(array $actualValues): float
    {
        if (empty($actualValues)) {
            return 0;
        }

        $mean = array_sum($actualValues) / count($actualValues);
        $squaredDiffs = array_map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $actualValues);

        $variance = array_sum($squaredDiffs) / (count($actualValues) - 1);
        return sqrt($variance / count($actualValues));
    }

    public function getAccuracyMetrics(): array
    {
        $historicalData = $this->getHistoricalData();
        
        if ($historicalData->count() < 12) {
            return [
                'mse' => null,
                'mae' => null,
                'accuracy_percentage' => null,
                'error' => 'Insufficient data for accuracy metrics'
            ];
        }

        // Use the last 3 months as test data
        $testData = $historicalData->slice(-3);
        $trainingData = $historicalData->slice(0, -3);

        [$trainingSamples, $trainingTargets] = $this->prepareTrainingData($trainingData);
        
        $regression = new LeastSquares();
        $regression->train($trainingSamples, $trainingTargets);

        $actualValues = [];
        $predictedValues = [];

        foreach ($testData as $index => $data) {
            $currentDate = Carbon::parse($data->date);
            $monthsDiff = Carbon::parse($trainingData->first()->date)->diffInMonths($currentDate);
            
            $prediction = $regression->predict([
                $monthsDiff,
                sin(2 * pi() * $currentDate->month / 12),
                cos(2 * pi() * $currentDate->month / 12),
            ]);

            $actualValues[] = $data->count;
            $predictedValues[] = $prediction;
        }

        // Calculate metrics
        $mse = $this->calculateMSE($actualValues, $predictedValues);
        $mae = $this->calculateMAE($actualValues, $predictedValues);
        $accuracyPercentage = $this->calculateAccuracyPercentage($actualValues, $predictedValues);

        return [
            'mse' => round($mse, 2),
            'mae' => round($mae, 2),
            'accuracy_percentage' => round($accuracyPercentage, 2),
            'error' => null
        ];
    }

    protected function calculateMSE(array $actual, array $predicted): float
    {
        return array_sum(array_map(function ($a, $p) {
            return pow($a - $p, 2);
        }, $actual, $predicted)) / count($actual);
    }

    protected function calculateMAE(array $actual, array $predicted): float
    {
        return array_sum(array_map(function ($a, $p) {
            return abs($a - $p);
        }, $actual, $predicted)) / count($actual);
    }

    protected function calculateAccuracyPercentage(array $actual, array $predicted): float
    {
        $totalError = array_sum(array_map(function ($a, $p) {
            return abs(($a - $p) / max($a, 1)) * 100;
        }, $actual, $predicted));
        
        return 100 - ($totalError / count($actual));
    }
}