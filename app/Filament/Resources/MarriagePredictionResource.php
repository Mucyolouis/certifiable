<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarriagePredictionResource\Pages;
use App\Models\Marriage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarriagePredictionResource extends Resource
{
    protected static ?string $model = Marriage::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Predictions';
    protected static ?string $navigationLabel = 'Marriage Predictions';
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['super_admin', 'pastor']);
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['super_admin', 'pastor']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('prediction_months')
                    ->label('Prediction Period')
                    ->options([
                        3 => '3 months',
                        6 => '6 months',
                        12 => '12 months',
                    ])
                    ->default(6)
                    ->required(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarriagePredictions::route('/'),
        ];
    }
}

namespace App\Filament\Resources\MarriagePredictionResource\Pages;

use App\Filament\Resources\MarriagePredictionResource;
use App\Filament\Widgets\MarriagePredictionStats;
use App\Services\MarriagePredictionService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class ListMarriagePredictions extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MarriagePredictionResource::class;
    protected static string $view = 'filament.resources.marriage-prediction.pages.list-marriage-predictions';
    
    public $predictionMonths = 6;
    public $predictions = [];
    public $metrics = [];

    public function mount(): void
    {
        $this->refreshPredictions();
    }

    public function refreshPredictions(): void
    {
        $predictionService = new MarriagePredictionService();
        $result = $predictionService->predictFutureMarriages($this->predictionMonths);
        $this->predictions = $result['predictions'];
        $this->metrics = $predictionService->getAccuracyMetrics();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            MarriagePredictionStats::class,
        ];
    }
}