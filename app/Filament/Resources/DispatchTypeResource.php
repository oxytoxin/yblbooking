<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispatchTypeResource\Pages;
use App\Filament\Resources\DispatchTypeResource\RelationManagers;
use App\Models\DispatchType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DispatchTypeResource extends Resource
{
    protected static ?string $model = DispatchType::class;

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';

    protected static ?string $navigationGroup = 'Dispatching';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('stops')
                    ->maxValue(10)
                    ->minValue(0)
                    ->integer()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('stops'),
                Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                    ->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Date Updated')
                    ->date(),
            ])
            ->prependActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (DispatchType $record) => $record->delete())
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
            'index' => Pages\ListDispatchTypes::route('/'),
            'create' => Pages\CreateDispatchType::route('/create'),
            'edit' => Pages\EditDispatchType::route('/{record}/edit'),
        ];
    }
}
