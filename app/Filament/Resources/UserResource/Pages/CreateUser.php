<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Settings\MailSettings;
use Exception;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use App\Models\Church;
use App\Models\Ministry;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Events\Registered;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('User Info')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('lastname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->required(),
                                Forms\Components\TextInput::make('mother_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('father_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('god_parent')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('church_id')
                                    ->label('Church')
                                    ->options(Church::all()->pluck('name', 'id'))
                                    ->required(),
                                Forms\Components\Select::make('ministry_id')
                                    ->label('Ministry')
                                    ->options(Ministry::all()->pluck('name', 'id'))
                                    ->required(),
                            ]),
                    ])->columns(2),
                Forms\Components\Wizard\Step::make('Login & Contact Info')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(255)
                            ->unique('users', 'username'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->rules(['digits:10'])
                            ->placeholder('0787654321')
                            ->validationAttribute('phone number')
                            ->helperText('Enter a 10-digit phone number'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->required()
                            ->email()
                            ->unique('users', 'email')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->same('password_confirmation'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->label('Confirm Password'),
                    ])->columns(2),
                Forms\Components\Wizard\Step::make('Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_photo_path')
                            ->image()
                            ->directory('profile-photos')
                            ->maxSize(1024)
                            ->label('Profile Photo'),
                    ])->columns(2),
            ])
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        $user = static::getModel()::create($data);

        // Assign the 'christian' role to the new user
        $christianRole = Role::where('name', 'christian')->first();
        if ($christianRole) {
            $user->assignRole($christianRole);
        }

        return $user;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        event(new Registered($user));

        Notification::make()
            ->title('User created successfully')
            ->success()
            ->send();
    }
}