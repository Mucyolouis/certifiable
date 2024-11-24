<?php

namespace App\Filament\Resources\BaptismPredictionResource\Pages;

use App\Filament\Resources\BaptismPredictionResource;
use App\Services\BaptismPredictionService;
use Filament\Resources\Pages\Page;
use Carbon\Carbon;

class ListBaptismPredictions extends Page
{
    protected static string $resource = BaptismPredictionResource::class;
    protected static string $view = 'filament.resources.baptism-prediction.pages.list-baptism-predictions';
    
    public $predictions = [];
    public $selectedMonth;
    
    public function mount(): void
    {
        $this->selectedMonth = Carbon::now()->addMonth()->month;
        $this->refreshPredictions();
    }

    public function refreshPredictions(): void
    {
        $predictionService = new BaptismPredictionService();
        $result = $predictionService->predictFutureBaptisms($this->selectedMonth);
        $this->predictions = $result['predictions'];
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