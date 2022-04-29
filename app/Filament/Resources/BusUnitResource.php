<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusUnitResource\Pages;
use App\Filament\Resources\BusUnitResource\RelationManagers;
use App\Models\BusClass;
use App\Models\BusUnit;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BusUnitResource extends Resource
{
    protected static ?string $model = BusUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Buses and Terminals';

    public static function form(Form $form): Form
    {
        $bus_classes = BusClass::get()->mapWithKeys(fn ($bc) => [$bc->id => $bc->description])->toArray();
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('platenumber')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('bus_class_id')
                    ->options($bus_classes)->required()->label('Bus Class'),
                Forms\Components\TextInput::make('passenger_capacity')
                    ->integer()
                    ->minValue(20)
                    ->maxValue(50)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bus_class.description')->searchable()->label('Bus Class'),
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('platenumber')->searchable(),
                Tables\Columns\TextColumn::make('passenger_capacity')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()->label('Date Created'),
            ])
            ->prependActions([
                Tables\Actions\LinkAction::make('delete')
                    ->action(fn (BusUnit $record) => $record->delete())
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
            'index' => Pages\ListBusUnits::route('/'),
            'create' => Pages\CreateBusUnit::route('/create'),
            'edit' => Pages\EditBusUnit::route('/{record}/edit'),
        ];
    }
}
