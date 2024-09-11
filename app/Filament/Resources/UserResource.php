<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Baptism;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Settings\MailSettings;
use Filament\Facades\Filament;
use App\Policies\BaptismPolicy;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use App\Notifications\UserBaptizedNotification;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static int $globalSearchResultsLimit = 20;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $navigationGroup = 'Access';

    public static function form(Form $form): Form
    {

        return $form
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\Grid::make()
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('media')
                                        ->hiddenLabel()
                                        ->avatar()
                                        ->collection('avatars')
                                        ->alignCenter()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('username')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('firstname')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('lastname')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\DatePicker::make('date_of_birth')
                                        ->native(false)
                                        ->minDate(now()->subYears(150))
                                        ->maxDate(now())
                                        ->required(),
                                    Forms\Components\TextInput::make('phone')
                                        ->required()
                                        ->maxLength(10)
                                        ->numeric()
                                        ->rule('digits:10'),
                                    Forms\Components\TextInput::make('mother_name')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('father_name')
                                        ->required()
                                        ->maxLength(255),
                                        Forms\Components\Select::make('church_id')
                                        ->label('Church')
                                        ->relationship('church', 'name')
                                        ->required(),
                                    Forms\Components\Select::make('ministry_id')
                                        ->label('Ministry')
                                        ->relationship('ministry', 'name')
                                        ->required(),
                                    
                

                                ]),
                        ])
                        ->columnSpan([
                            'sm' => 1,
                            'lg' => 2
                        ]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Role')
                                ->schema([
                                    Select::make('roles')->label('Role')
                                        ->hiddenLabel()
                                        ->relationship('roles', 'name')
                                        ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
                                        ->multiple()
                                        ->preload()
                                        ->maxItems(1)
                                        ->native(false),
                                ])
                                ->compact(),
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->revealable()
                            ->rule(Password::default()),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->dehydrated(false)
                            ->revealable()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password')
                            ->validationAttribute('password confirmation'),
                    ])
                                ->compact()
                                ->hidden(fn (string $operation): bool => $operation === 'edit'),
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('email_verified_at')
                                        ->label(__('resource.general.email_verified_at'))
                                        ->content(fn (User $record): ?string => $record->email_verified_at),
                                    Forms\Components\Actions::make([
                                        Action::make('resend_verification')
                                            ->label(__('resource.user.actions.resend_verification'))
                                            ->color('secondary')
                                            ->action(fn (MailSettings $settings, Model $record) => static::doResendEmailVerification($settings, $record)),
                                    ])
                                    ->hidden(fn (User $user) => $user->email_verified_at != null)
                                    ->fullWidth(),
                                    Forms\Components\Placeholder::make('created_at')
                                        ->label(__('resource.general.created_at'))
                                        ->content(fn (User $record): ?string => $record->created_at?->diffForHumans()),
                                    Forms\Components\Placeholder::make('updated_at')
                                        ->label(__('resource.general.updated_at'))
                                        ->content(fn (User $record): ?string => $record->updated_at?->diffForHumans()),
                                ])
                                ->hidden(fn (string $operation): bool => $operation === 'create'),
                        ])
                        ->columnSpan(1),
                ])
                ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make('username')->label('Username')
                    ->description(fn (Model $record) => $record->firstname.' '.$record->lastname)
                    ->searchable(['firstname', 'lastname']),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge(),
                    Tables\Columns\TextColumn::make('church.name')
                    ->label('Church')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('baptized')
                    ->label('Baptized')
                    ->sortable(),
                Tables\Columns\TextColumn::make('marital_status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->label('Verified at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                //
                Tables\Filters\Filter::make('baptized')
                    ->query(fn (Builder $query): Builder => $query->where('baptized', true))
                    ->label('Baptized Christians')
                    ->default(false),
                Tables\Filters\SelectFilter::make('trashed')
                ->options([
                    'with' => 'With Trashed',
                    'only' => 'Only Trashed',
                    'without' => 'Without Trashed',
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['value'] === 'with') {
                        $query->withTrashed();
                    } elseif ($data['value'] === 'only') {
                        $query->onlyTrashed();
                    }
                })
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('markAsBaptized')
                        ->action(function (User $record) {

                            //query to baptise a user
                            $record->update([
                                'baptized' => 1,
                                'baptized_at' => now(),
                            ]);

                             // Send notification using email
                            $record->notify(new UserBaptizedNotification($record));

                            //notification visible on view
                            Notification::make()
                            ->title('User marked as baptized')
                            ->success()
                            ->send();
                        })
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->visible(function (User $record) {
                            $currentUser = Auth::user();
                            return $currentUser->hasRole('pastor') && $record->baptized == 0 && $currentUser->baptized == 1;
                        })
                        ->hidden(fn (User $record) => $record->baptized)
                        ->authorize(fn () => Auth::user()->hasRole('pastor')),

                    //Impersonate::make('Impersonate')->label('Impersonate'),

                    Tables\Actions\EditAction::make()
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Impersonate::make()
                    //->redirect(fn (User $record) => $record->redirectTo(route('/admin'))),
                ])
                
            ])
            ->bulkActions([
                
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->email;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'firstname', 'lastname'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->firstname.' '.$record->lastname,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.access");
    }

    public static function doResendEmailVerification($settings = null, $user): void
    {
        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        $notification = new VerifyEmail();
        $notification->url = Filament::getVerifyEmailUrl($user);

        $settings->loadMailSettingsToConfig();

        $user->notify($notification);

        Notification::make()
            ->title(__('resource.user.notifications.notification_resent.title'))
            ->success()
            ->send();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
