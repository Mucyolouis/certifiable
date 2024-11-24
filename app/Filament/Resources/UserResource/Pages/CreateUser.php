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
    public ?string $role = 'christian';  // Add this property

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
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('lastname')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->required()
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('mother_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('father_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('god_parent')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\Select::make('church_id')
                                    ->label('Church')
                                    ->options(Church::all()->pluck('name', 'id'))
                                    ->required()
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\Select::make('ministry_id')
                                    ->label('Ministry')
                                    ->options(Ministry::all()->pluck('name', 'id'))
                                    ->required()
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                            ])
                            ->columns(12),
                    ]),
                Forms\Components\Wizard\Step::make('Login & Contact Info')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('username')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('users', 'username')
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->rules(['digits:10'])
                                    ->placeholder('0787654321')
                                    ->validationAttribute('phone number')
                                    ->helperText('Enter a 10-digit phone number')
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email address')
                                    ->required()
                                    ->email()
                                    ->unique('users', 'email')
                                    ->maxLength(255)
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->minLength(8)
                                    ->same('password_confirmation')
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                                Forms\Components\TextInput::make('password_confirmation')
                                    ->password()
                                    ->required()
                                    ->minLength(8)
                                    ->label('Confirm Password')
                                    ->columnSpan(['default' => 12, 'sm' => 6]),
                            ])
                            ->columns(12),
                    ]),
                Forms\Components\Wizard\Step::make('Photo')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\FileUpload::make('profile_photo_path')
                                    ->image()
                                    ->directory('profile-photos')
                                    ->maxSize(1024)
                                    ->label('Profile Photo')
                                    ->columnSpan(12),
                            ])
                            ->columns(12),
                    ]),
                Forms\Components\Wizard\Step::make('Role Assignment')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\Select::make('role')
                                    ->label('Select Role')
                                    ->options(Role::all()->pluck('name', 'name'))
                                    ->required()
                                    ->columnSpan(12)
                                    ->default('christian')
                                    ->live(),
                            ])
                            ->columns(12),
                    ]),
            ])
            ->columnSpanFull(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->role = $data['role'] ?? 'christian';
        unset($data['role']);
        
        $data['name'] = $data['firstname']." ".$data['lastname'];
        $data['password'] = Hash::make($data['password']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Assign the selected role
        $role = Role::where('name', $this->role)->first();
        if ($role) {
            $user->assignRole($role);
        }

        event(new Registered($user));

        Notification::make()
            ->title('User created successfully')
            ->success()
            ->send();
    }
}