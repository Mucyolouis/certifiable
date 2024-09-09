<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\TransferPredictionService;

class TransferPredictionDashboardController extends Controller
{
    protected $transferPredictionService;

    public function __construct(TransferPredictionService $transferPredictionService)
    {
        $this->transferPredictionService = $transferPredictionService;
    }

    public function index()
    {
        $dashboardData = $this->getDashboardData();
        return Inertia::render('TransferPredictionDashboard', ['dashboardData' => $dashboardData]);
    }

    private function getDashboardData()
    {
        // Fetch data from your TransferPredictionService
        $transferRequests = $this->transferPredictionService->getTransferRequests();

        // Process the data for the dashboard
        $approvalStatus = $this->getApprovalStatusData($transferRequests);
        $roleDistribution = $this->getRoleDistributionData($transferRequests);
        $churchTransfers = $this->getChurchTransfersData($transferRequests);
        $featureImportance = $this->getFeatureImportanceData();

        return [
            'approvalStatus' => $approvalStatus,
            'roleDistribution' => $roleDistribution,
            'churchTransfers' => $churchTransfers,
            'featureImportance' => $featureImportance,
        ];
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
}
