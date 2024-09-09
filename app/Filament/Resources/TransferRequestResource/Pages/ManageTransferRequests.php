<?php

namespace App\Filament\Resources\TransferRequestResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\TransferRequestResource;

class ManageTransferRequests extends ManageRecords
{
    protected static string $resource = TransferRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function create(): void
    {
        if (!TransferRequestResource::canCreate()) {
            Notification::make()
                ->warning()
                ->title('Action not allowed')
                ->body('You already have a pending transfer request.')
                ->send();
            return;
        }

        parent::create();
    }
}
