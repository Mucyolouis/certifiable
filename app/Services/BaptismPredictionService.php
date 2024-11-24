<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BaptismPredictionService
{
    public function getHistoricalData()
    {
        return User::where('baptized', true)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m-01") as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('baptized_at')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-01")'))
            ->orderBy('date')
            ->get();
    }

    public function predictFutureBaptisms(): array
    {
        try {
            // Get basic statistics
            $totalBaptized = User::where('baptized', true)->count();
            $totalUnbaptized = User::where('baptized', false)->count();
            $recentBaptisms = User::where('baptized', true)
                ->whereMonth('created_at', '>=', Carbon::now()->subMonths(3))
                ->count();

            if ($totalBaptized == 0 && $totalUnbaptized == 0) {
                return [
                    'predictions' => [],
                    'error' => 'No user data available'
                ];
            }

            // Calculate base prediction rate
            $baseRate = $recentBaptisms > 0 ? 
                       $recentBaptisms / 3 : // Use recent average if available
                       max(1, $totalBaptized / max(1, Carbon::now()->diffInMonths(User::min('created_at'))));

            $predictions = [];
            $currentMonth = Carbon::now();

            // Generate predictions for next 6 months
            for ($i = 1; $i <= 6; $i++) {
                $month = $currentMonth->copy()->addMonths($i);
                
                // Apply seasonal factors
                $seasonalFactor = $this->getSeasonalFactor($month->month);
                $predictedCount = round($baseRate * $seasonalFactor);
                
                // Calculate confidence range
                $range = [
                    'min' => max(0, round($predictedCount * 0.7)),
                    'max' => round($predictedCount * 1.3)
                ];

                $predictions[] = [
                    'month' => $month->format('F Y'),
                    'predicted_baptisms' => $predictedCount,
                    'range' => $range,
                    'unbaptized_pool' => $totalUnbaptized,
                    'seasonal_factor' => $seasonalFactor
                ];
            }

            return [
                'predictions' => $predictions,
                'error' => null,
                'statistics' => [
                    'total_baptized' => $totalBaptized,
                    'total_unbaptized' => $totalUnbaptized,
                    'recent_monthly_average' => round($baseRate, 1),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'predictions' => [],
                'error' => 'Error generating prediction: ' . $e->getMessage()
            ];
        }
    }

    private function getSeasonalFactor(int $month): float
    {
        // Seasonal factors (adjust these based on your domain knowledge)
        $seasonalFactors = [
            1 => 1.0,  // January
            2 => 0.9,  // February
            3 => 1.1,  // March
            4 => 1.2,  // April (Easter season)
            5 => 1.3,  // May
            6 => 1.4,  // June
            7 => 1.2,  // July
            8 => 1.1,  // August
            9 => 1.0,  // September
            10 => 0.9, // October
            11 => 0.8, // November
            12 => 1.1  // December (Christmas season)
        ];

        return $seasonalFactors[$month] ?? 1.0;
    }
}