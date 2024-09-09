<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChurchResource\Pages;
use App\Filament\Resources\ChurchResource\RelationManagers;
use App\Models\Church;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChurchResource extends Resource
{
    protected static ?string $model = Church::class;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Services';
    protected static ?string $modelLabel = 'Local Church';
    protected static ?string $pluralModelLabel = 'Local Churches';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('parish_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parish.name')
                    ->label('Parish')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record, $state) {
                        $parishName = $record->parish?->name ?? 'N/A';
                        return view('filament.tables.columns.text-column', [
                            'column' => [
                                'view' => 'filament::tables.columns.text-column',
                                'data' => $parishName,
                            ],
                        ]);
                    })                    
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Church')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_summary')
                    ->label('Summary')
                    ->getStateUsing(fn (Church $record) => $record->users_summary)
                    ->html()
                    ->formatStateUsing(fn (string $state) => nl2br(e($state)))
                    ->searchable(false)
                    ->sortable(false),
            ])
        
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\Action::make('users')
                //     ->label('View Users')
                //     ->url(fn () => $this->getResource()::getUrl('users', ['record' => $this->record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageChurches::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->withCount([
            'users as total_users',
            'users as baptized_christians_count' => function (Builder $query) {
                $query->role('christian')->where('baptized', 1);
            },
            'users as unbaptized_count' => function (Builder $query) {
                $query->where('baptized', 0);
            },
            'users as pastors_count' => function (Builder $query) {
                $query->role('pastor');
            },
        ]);
    }
}
