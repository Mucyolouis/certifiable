<?php

namespace App\Filament\Resources\TransferRequestPredictionResource\Pages;

use App\Filament\Resources\TransferRequestPredictionResource;
use App\Services\TransferRequestService;
use App\Models\Church;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Carbon\Carbon;

class ViewTransferPredictions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TransferRequestPredictionResource::class;
    protected static string $view = 'filament.resources.transfer-request-prediction.pages.view-transfer-predictions';
    
    public $predictions = [];
    public $statistics = [];
    public $selectedMonth;
    public $selectedChurch = 'all';
    public $direction = 'both'; // 'from', 'to', or 'both'

    public function mount(): void
    {
        $this->selectedMonth = Carbon::now()->addMonth()->month;
        $this->refreshPredictions();
    }

    public function refreshPredictions(): void
    {
        $service = new TransferRequestService();
        
        // Get predictions with filters
        $result = $service->predictTransfers(
            $this->selectedMonth,
            $this->selectedChurch !== 'all' ? $this->selectedChurch : null,
            $this->direction
        );
        
        $this->predictions = $result['predictions'];
        $this->statistics = $service->getTransferStatistics(
            $this->selectedChurch !== 'all' ? $this->selectedChurch : null,
            $this->direction
        );
    }

    protected function getViewData(): array
    {
        return [
            'churches' => Church::orderBy('name')->get(),
            'futureMonths' => $this->getFutureMonths(),
            'directions' => [
                'both' => 'Both Directions',
                'from' => 'Transfers From',
                'to' => 'Transfers To'
            ]
        ];
    }

    protected function getFutureMonths(): array
    {
        $months = [];
        $current = Carbon::now();
        
        for ($i = 1; $i <= 12; $i++) {
            $future = $current->copy()->addMonths($i);
            $months[$future->month] = $future->format('F Y');
        }

        return $months;
    }
}