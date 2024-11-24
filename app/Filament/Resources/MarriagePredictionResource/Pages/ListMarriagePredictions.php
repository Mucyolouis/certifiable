<?php

namespace App\Filament\Resources\MarriagePredictionResource\Pages;

use App\Filament\Resources\MarriagePredictionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarriagePredictions extends ListRecords
{
    protected static string $resource = MarriagePredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
