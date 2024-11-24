<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use App\Models\Church;
use App\Models\Ministry;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Http\Responses\Auth\RegistrationResponse as DefaultRegistrationResponse;

class Register extends BaseRegister
{

    protected static string $view = 'filament.pages.auth.register';
    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        $user = $this->getUserModel()::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'name' => $data['firstname']." ".$data['lastname'],
            'date_of_birth' => $data['date_of_birth'],
            'mother_name' => $data['mother_name'],
            'father_name' => $data['father_name'],
            'god_parent' => $data['god_parent'],
            'church_id' => $data['church_id'],
            'ministry_id' => $data['ministry_id'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'profile_photo_path' => $data['profile_photo_path'] ?? null,
        ]);

        // Assign the 'christian' role to the new user
        $christianRole = Role::where('name', 'christian')->first();
        if ($christianRole) {
            $user->assignRole($christianRole);
        }

        event(new Registered($user));

        Notification::make()
            ->title('Registered successfully')
            ->success()
            ->send();

        return new DefaultRegistrationResponse($user);
    }
}
