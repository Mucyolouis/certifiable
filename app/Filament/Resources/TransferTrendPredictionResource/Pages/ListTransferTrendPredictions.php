<?php

namespace App\Filament\Resources\TransferTrendPredictionResource\Pages;

use App\Filament\Resources\TransferTrendPredictionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransferTrendPredictions extends ListRecords
{
    protected static string $resource = TransferTrendPredictionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
