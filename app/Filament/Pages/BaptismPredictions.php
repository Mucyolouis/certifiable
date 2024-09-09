<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Tables;
use App\Services\BaptismPrediction;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class BaptismPredictions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.baptism-predictions';

    protected static ?string $title = 'Baptism Predictions';

    protected BaptismPrediction $baptismPrediction;

    public function mount(): void
    {
        $this->baptismPrediction = new BaptismPrediction();
        $this->baptismPrediction->trainModel();
    }

    protected function getTableQuery(): Builder
    {
        return User::query()->whereNull('baptized_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('firstname')->searchable(),
            TextColumn::make('lastname')->searchable(),
            TextColumn::make('date_of_birth')->date(),
            TextColumn::make('church.name')->searchable(),
            TextColumn::make('ministry.name')->searchable(),
            TextColumn::make('active_status')->boolean(),
            TextColumn::make('marital_status'),
            TextColumn::make('baptism_likelihood')
                ->getStateUsing(function (User $record): string {
                    $prediction = $this->baptismPrediction->predict([
                        $this->baptismPrediction->calculateAge($record->date_of_birth),
                        $record->church_id,
                        $record->ministry_id,
                        $record->active_status,
                        $this->baptismPrediction->encodeMaritalStatus($record->marital_status),
                    ]);
                    return $prediction[0] ? 'Likely' : 'Unlikely';
                }),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('church')
                ->relationship('church', 'name'),
            Tables\Filters\SelectFilter::make('ministry')
                ->relationship('ministry', 'name'),
            Tables\Filters\SelectFilter::make('marital_status')
                ->options([
                    'single' => 'Single',
                    'married' => 'Married',
                ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // You can add custom actions here if needed
        ];
    }

    private function trainModel(): void
    {
        $predictor = new BaptismPredictions();
        $dataset = $predictor->prepareData();
        $predictor->trainModel($dataset);
    }

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['pastor', 'super_admin']);
    }
}