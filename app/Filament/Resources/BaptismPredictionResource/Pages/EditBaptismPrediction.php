<?php

namespace App\Filament\Resources\BaptismPredictionResource\Pages;

use App\Filament\Resources\BaptismPredictionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBaptismPrediction extends EditRecord
{
    protected static string $resource = BaptismPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
