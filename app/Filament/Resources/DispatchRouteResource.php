<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispatchRouteResource\Pages;
use App\Filament\Resources\DispatchRouteResource\RelationManagers;
use App\Models\DispatchRoute;
use App\Models\DispatchType;
use App\Models\Terminal;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DispatchRouteResource extends Resource
{
    protected static ?string $model = DispatchRoute::class;

    protected static ?string $navigationIcon = 'heroicon-o-trending-up';

    protected static ?string $navigationGroup = 'Dispatching';

    public static function form(Form $form): Form
    {
        $dispatch_types = DispatchType::get()->mapWithKeys(fn ($dt) => [$dt->id => $dt->name])->toArray();
        $terminals = Terminal::get()->mapWithKeys(fn ($dt) => [$dt->id => $dt->name])->toArray();
        return $form
            ->schema([
                Forms\Components\Select::make('from_terminal')
                    ->label('Origin')
                    ->options($terminals)
                    ->required(),
                Forms\Components\Select::make('to_terminal')
                    ->label('Destination')
                    ->options($terminals)
                    ->required(),
                Forms\Components\TextInput::make('distance_in_km')->label('Distance (km)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('fare')->label('Fare (PHP)')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('origin.name'),
                Tables\Columns\TextColumn::make('destination.name'),
                Tables\Columns\TextColumn::make('distance_in_km')->formatStateUsing(function ($state) {
                    return $state . ' km.';
                })->label('Distance'),
                Tables\Columns\TextColumn::make('fare')->money('php', shouldConvert: true),
                Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                    ->date(),
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
            'index' => Pages\ListDispatchRoutes::route('/'),
            'create' => Pages\CreateDispatchRoute::route('/create'),
            'edit' => Pages\EditDispatchRoute::route('/{record}/edit'),
        ];
    }
}
