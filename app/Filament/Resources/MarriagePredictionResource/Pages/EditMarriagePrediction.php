<?php

namespace App\Filament\Resources\MarriagePredictionResource\Pages;

use App\Filament\Resources\MarriagePredictionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarriagePrediction extends EditRecord
{
    protected static string $resource = MarriagePredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
