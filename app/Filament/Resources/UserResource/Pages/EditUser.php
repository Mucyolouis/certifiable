<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Filament\Support;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Enums\Alignment;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;
use App\Notifications\PasswordUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\CreateAction::make()
                    ->label('Create')
                    ->url(fn (): string => static::$resource::getNavigationUrl().'/create'),
                Actions\EditAction::make()
                    ->label('Change password')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->revealable()
                            ->required(),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->revealable()
                            ->same('password')
                            ->required(),
                    ])
                    ->modalWidth(Support\Enums\MaxWidth::Medium)
                    ->modalHeading('Update Password')
                    ->modalDescription(fn ($record) => $record->email)
                    ->modalAlignment(Alignment::Center)
                    ->modalCloseButton(false)
                    ->modalSubmitActionLabel('Submit')
                    ->modalCancelActionLabel('Cancel')
                    ->onSubmit(function (array $data): void {
                        $user = $this->getRecord();
                        $user->password = Hash::make($data['password']);
                        $user->save();

                        // Send email notification after successful update
                        $user->notify(new PasswordUpdatedNotification());

                        $this->notify('Password updated successfully!');
                        $this->redirect(static::getResource()::getUrl('view', [$user->id]));
                    }),
                Actions\DeleteAction::make(),
            ])
            ->hiddenLabel()
            ->button()
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getActions(): array
    {
        return [
            Impersonate::make()->record($this->getRecord()),
        ];
    }
}
