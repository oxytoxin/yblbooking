<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\FileUpload::make('image')
                        ->required()
                        ->image(),
                    Forms\Components\Select::make('role_id')
                        ->label('Recipients')
                        ->options([
                            Role::PASSENGER => 'Passengers',
                            Role::CONDUCTOR => 'Conductors',
                        ])
                        ->default(Role::PASSENGER)
                        ->required(),
                ]),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->maxLength(65535)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role_id')->label('Recipient')->formatStateUsing(fn ($state) => match ($state) {
                    Role::PASSENGER => 'Passengers',
                    Role::CONDUCTOR => 'Conductors',
                }),
                Tables\Columns\ImageColumn::make('image')->url(fn ($record) => '/storage/' . $record->image),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
