<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\TransferPredictionService;
use Illuminate\Support\Collection;

class TransferPredictionDashboard extends Component
{
    public $approvalStatus;
    public $roleDistribution;
    public $churchTransfers;
    public $featureImportance;
    public $transferReasons;

    protected $transferPredictionService;

    public function boot(TransferPredictionService $transferPredictionService)
    {
        $this->transferPredictionService = $transferPredictionService;
    }

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function render()
    {
        
        return view('livewire.transfer-prediction-dashboard');
    }

    private function loadDashboardData()
    {
        $transferRequests = $this->transferPredictionService->getTransferRequests();

        $this->approvalStatus = $this->getApprovalStatusData($transferRequests);
        $this->roleDistribution = $this->getRoleDistributionData($transferRequests);
        $this->churchTransfers = $this->getChurchTransfersData($transferRequests);
        $this->featureImportance = $this->getFeatureImportanceData();
        $this->transferReasons = $this->getTransferReasonsData();
    }

    private function getApprovalStatusData($transferRequests)
    {
        $approved = $transferRequests->where('approval_status', 'approved')->count();
        $rejected = $transferRequests->where('approval_status', 'rejected')->count();

        return [
            ['name' => 'Approved', 'value' => $approved],
            ['name' => 'Rejected', 'value' => $rejected],
        ];
    }

    private function getRoleDistributionData($transferRequests)
    {
        return $transferRequests->groupBy('christian.role')
            ->map(function ($group) {
                return ['name' => $group->first()->christian->role, 'value' => $group->count()];
            })->values()->all();
    }

    private function getChurchTransfersData($transferRequests)
    {
        return $transferRequests->groupBy(function ($item) {
            return $item->fromChurch->name . ' to ' . $item->toChurch->name;
        })
            ->map(function ($group, $key) {
                return ['name' => $key, 'value' => $group->count()];
            })
            ->sortByDesc('value')
            ->take(5)
            ->values()
            ->all();
    }

    private function getFeatureImportanceData()
    {
        // This would typically come from your model's feature importance scores
        // For now, we'll use dummy data
        return [
            ['name' => 'Role', 'value' => 0.3],
            ['name' => 'From Church', 'value' => 0.2],
            ['name' => 'To Church', 'value' => 0.2],
            ['name' => 'Age', 'value' => 0.15],
            ['name' => 'Gender', 'value' => 0.15],
        ];
    }


    private function getTransferReasonsData()
    {
        // Ensure the model is trained before calling analyzeTransferReasons
        $this->transferPredictionService->train();
        
        $reasonsAnalysis = $this->transferPredictionService->analyzeTransferReasons();
        
        return collect($reasonsAnalysis)->map(function ($stats, $reason) {
            return [
                'name' => $reason,
                'total' => $stats['total'],
                'approved' => $stats['approved'],
                'approval_rate' => $stats['approval_rate']
            ];
        })->sortByDesc('total')->values();
    }


    public function getTransferReasonsForChart()
    {
        return [
            'labels' => $this->transferReasons->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Approval Rate',
                    'data' => $this->transferReasons->pluck('approval_rate')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }
}