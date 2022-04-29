<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusClassResource\Pages;
use App\Filament\Resources\BusClassResource\RelationManagers;
use App\Models\BusClass;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BusClassResource extends Resource
{
    protected static ?string $model = BusClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-database';

    protected static ?string $navigationGroup = 'Buses and Terminals';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()->label('Date Created'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()->label('Date Updated'),
            ])
            ->prependActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (BusClass $record) => $record->delete())
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListBusClasses::route('/'),
            'create' => Pages\CreateBusClass::route('/create'),
            'edit' => Pages\EditBusClass::route('/{record}/edit'),
        ];
    }
}
