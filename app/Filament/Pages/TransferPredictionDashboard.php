<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Policies\TransferPredictionDashboardPolicy;
use App\Services\TransferPredictionService;

class TransferPredictionDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.transfer-prediction-dashboard';

    public static function shouldRegister(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['pastor', 'super_admin']);
    }

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['pastor', 'super_admin']);
    }

    public function getTitle(): string
    {
        return 'Transfer Prediction Dashboard';
    }
    
    public static function getPolicy(): string
    {
        return TransferPredictionDashboardPolicy::class;
    }

    public function getPredictedTransferPercentage()
    {
        $predictionService = new TransferPredictionService();
        try {
            $percentage = $predictionService->predictPercentageOfTransfers();
            return "We predict that {$percentage}% of Christians will request transfer.";
        } catch (\Exception $e) {
            return "Unable to generate prediction at this time. Error: " . $e->getMessage();
        }
    }
}