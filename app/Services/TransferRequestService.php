<?php

namespace App\Services;

use App\Models\TransferRequest;
use App\Models\Church;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TransferRequestService
{
    public function predictTransfers(int $selectedMonth, ?string $churchId = null, string $direction = 'both'): array
    {
        try {
            $query = TransferRequest::query();

            if ($churchId) {
                $query->where(function($q) use ($churchId, $direction) {
                    if ($direction === 'from' || $direction === 'both') {
                        $q->orWhere('from_church_id', $churchId);
                    }
                    if ($direction === 'to' || $direction === 'both') {
                        $q->orWhere('to_church_id', $churchId);
                    }
                });
            }

            $historicalData = $this->getHistoricalData($query);
            
            if ($historicalData->isEmpty()) {
                return [
                    'predictions' => [],
                    'error' => 'Insufficient historical data for prediction'
                ];
            }

            $currentMonth = Carbon::now()->month;
            $selectedDate = Carbon::now()->month($selectedMonth);
            
            if ($selectedMonth <= $currentMonth) {
                $selectedDate->addYear();
            }

            $prediction = [
                'month' => $selectedDate->format('F Y'),
                'predicted_transfers' => $this->calculatePrediction($historicalData, $selectedMonth),
                'confidence_range' => $this->calculateConfidenceRange($historicalData, $selectedMonth),
                'likely_reasons' => $this->analyzePredictedReasons($historicalData, $selectedMonth),
                'approval_rate' => $this->predictApprovalRate($historicalData, $selectedMonth),
                'popular_routes' => $this->getPopularTransferRoutes($selectedMonth, $churchId, $direction)
            ];

            return [
                'predictions' => [$prediction],
                'error' => null
            ];

        } catch (\Exception $e) {
            return [
                'predictions' => [],
                'error' => 'Error generating prediction: ' . $e->getMessage()
            ];
        }
    }

    private function getHistoricalData(Builder $query)
    {
        return $query->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-01") as date'),
            DB::raw('COUNT(*) as count'),
            'approval_status',
            'description',
            'from_church_id',
            'to_church_id'
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(24))
        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-01")'), 'approval_status')
        ->orderBy('date')
        ->get();
    }

    private function calculatePrediction($historicalData, $month): int
    {
        $monthData = $historicalData->filter(function ($data) use ($month) {
            return Carbon::parse($data->date)->month === $month;
        });

        $baseCount = $monthData->avg('count') ?? 0;
        $growthTrend = $this->calculateGrowthTrend($historicalData);
        
        return max(0, round($baseCount * (1 + $growthTrend)));
    }

    private function calculateConfidenceRange($historicalData, $month): array
    {
        $monthData = $historicalData->filter(function ($data) use ($month) {
            return Carbon::parse($data->date)->month === $month;
        });

        $std = $monthData->std('count') ?? 1;
        $predicted = $this->calculatePrediction($historicalData, $month);

        return [
            'min' => max(0, round($predicted - $std)),
            'max' => round($predicted + $std)
        ];
    }

    private function analyzePredictedReasons($historicalData, $month): array
    {
        $keywords = [
            'work' => ['job', 'work', 'career', 'business'],
            'family' => ['family', 'marriage', 'spouse'],
            'education' => ['school', 'university', 'study'],
            'relocation' => ['moving', 'relocated', 'new home'],
        ];

        $reasons = [];
        foreach ($keywords as $category => $terms) {
            $count = 0;
            foreach ($terms as $term) {
                $count += $historicalData->filter(function ($data) use ($term) {
                    return stripos($data->description ?? '', $term) !== false;
                })->count();
            }
            if ($count > 0) {
                $reasons[$category] = $count;
            }
        }

        arsort($reasons);
        return array_slice($reasons, 0, 3, true);
    }

    private function predictApprovalRate($historicalData, $month): array
    {
        $monthData = $historicalData->filter(function ($data) use ($month) {
            return Carbon::parse($data->date)->month === $month;
        });

        $total = $monthData->count();
        if ($total === 0) {
            return ['approved' => 0, 'pending' => 0, 'rejected' => 0];
        }

        $approved = $monthData->where('approval_status', 'Approved')->count();
        $rejected = $monthData->where('approval_status', 'Rejected')->count();
        $pending = $monthData->where('approval_status', 'Pending')->count();

        return [
            'approved' => round(($approved / $total) * 100),
            'pending' => round(($pending / $total) * 100),
            'rejected' => round(($rejected / $total) * 100)
        ];
    }

    private function getPopularTransferRoutes($month, $churchId = null, $direction = 'both'): array
    {
        $query = DB::table('transfer_requests')
            ->select(
                'from_church_id',
                'to_church_id',
                DB::raw('COUNT(*) as count')
            )
            ->whereMonth('created_at', $month);

        if ($churchId) {
            $query->where(function($q) use ($churchId, $direction) {
                if ($direction === 'from' || $direction === 'both') {
                    $q->orWhere('from_church_id', $churchId);
                }
                if ($direction === 'to' || $direction === 'both') {
                    $q->orWhere('to_church_id', $churchId);
                }
            });
        }

        return $query->groupBy('from_church_id', 'to_church_id')
            ->orderByDesc('count')
            ->limit(3)
            ->get()
            ->map(function ($route) {
                $fromChurch = Church::find($route->from_church_id);
                $toChurch = Church::find($route->to_church_id);
                return [
                    'from' => $fromChurch?->name ?? 'Unknown Church',
                    'to' => $toChurch?->name ?? 'Unknown Church',
                    'count' => $route->count
                ];
            })
            ->toArray();
    }

    private function calculateGrowthTrend($historicalData): float
    {
        if ($historicalData->count() < 2) return 0;

        $oldest = $historicalData->first();
        $newest = $historicalData->last();
        
        if (!$oldest || !$newest) return 0;
        
        $monthsDiff = Carbon::parse($oldest->date)->diffInMonths(Carbon::parse($newest->date));

        if ($monthsDiff == 0 || $oldest->count == 0) return 0;

        return ($newest->count - $oldest->count) / ($oldest->count * $monthsDiff);
    }

    public function getTransferStatistics(?string $churchId = null, string $direction = 'both'): array
    {
        $query = TransferRequest::query();

        if ($churchId) {
            $query->where(function($q) use ($churchId, $direction) {
                if ($direction === 'from' || $direction === 'both') {
                    $q->orWhere('from_church_id', $churchId);
                }
                if ($direction === 'to' || $direction === 'both') {
                    $q->orWhere('to_church_id', $churchId);
                }
            });
        }

        $totals = $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN approval_status = "Approved" THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN approval_status = "Rejected" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN approval_status = "Pending" THEN 1 ELSE 0 END) as pending
        ')->first();

        if (!$totals) {
            return [
                'totals' => ['total' => 0, 'approved' => 0, 'rejected' => 0, 'pending' => 0],
                'approval_rate' => 0
            ];
        }

        return [
            'totals' => [
                'total' => $totals->total,
                'approved' => $totals->approved,
                'rejected' => $totals->rejected,
                'pending' => $totals->pending,
            ],
            'approval_rate' => $totals->total > 0 
                ? round(($totals->approved / $totals->total) * 100, 1) 
                : 0
        ];
    }
}