<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;
use Pages\ListBaptismPredictions;
use App\Filament\Resources\BaptismPredictionResource\Pages;

class BaptismPredictionResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $slug = 'baptism-predictions';
    protected static ?string $navigationGroup = 'Predictions';
    protected static ?string $navigationLabel = 'Baptism Predictions';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['super_admin', 'pastor']);
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['super_admin', 'pastor']);
    }



    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBaptismPredictions::route('/'),
        ];
    }
}