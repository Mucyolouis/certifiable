<?php

namespace App\Filament\Resources;

use App\Models\TransferRequest;
use Filament\Resources\Resource;
use App\Filament\Resources\TransferRequestPredictionResource\Pages;

class TransferRequestPredictionResource extends Resource
{
    protected static ?string $model = TransferRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    //protected static ?string $navigationIcon = 'rpg-recycle';
    protected static ?string $navigationGroup = 'Predictions';
    protected static ?string $navigationLabel = 'Transfer Predictions';
    protected static ?string $slug = 'transfer-predictions';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'pastor']);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['super_admin', 'pastor']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ViewTransferPredictions::route('/'),
        ];
    }
}