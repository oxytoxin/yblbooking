<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Dispatch;
use Filament\Resources\Form;
use App\Models\DispatchRoute;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\DispatchResource\Pages;
use App\Filament\Resources\DispatchResource\RelationManagers;
use App\Models\Booking;
use App\Models\BusUnit;
use App\Models\DispatchType;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DispatchResource extends Resource
{
    protected static ?string $model = Dispatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Dispatching';

    public static function form(Form $form): Form
    {
        $routes = DispatchRoute::get();
        $dispatch_types = DispatchType::get()->mapWithKeys(fn ($dt) => [$dt->id => $dt->name])->toArray();
        $bus_units = BusUnit::get()->mapWithKeys(fn ($bus) => [$bus->id => $bus->full_description])->toArray();

        return $form
            ->schema([
                Forms\Components\Select::make('dispatch_route_id')
                    ->options($routes->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray())
                    ->label('Route')
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('dispatch_type_id')
                    ->options($dispatch_types)
                    ->label('Type')
                    ->required()
                    ->default(1)
                    ->reactive(),
                DateTimePicker::make('schedule')
                    ->weekStartsOnSunday()
                    ->displayFormat('M j, Y h:i A')
                    ->withoutSeconds()
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('bus_unit_id')
                    ->label('Bus Unit Code')
                    ->options($bus_units)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        Dispatch::AVAILABLE => 'AVAILABLE',
                        Dispatch::DEPARTED => 'DEPARTED',
                    ])
                    ->default(Dispatch::AVAILABLE),
                Forms\Components\BelongsToManyCheckboxList::make('available_dispatch_routes')
                    ->label('Add Available Routes')
                    ->relationship('available_dispatch_routes', 'from_terminal', function (Builder $query, Closure $get) {
                        return $query->whereNot('id', $get('dispatch_route_id'));
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return $record->dispatch_route_name;
                    }),

            ]);
    }


    public static function table(Table $table): Table
    {
        $routes = DispatchRoute::get()->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray();
        return $table
            ->defaultSort('schedule')
            ->columns([
                Tables\Columns\TextColumn::make('bus_unit.code')->searchable()->label('Bus'),
                Tables\Columns\BadgeColumn::make('status_name')
                    ->colors([
                        'warning' => 'Departed',
                        'success' => 'Available',
                    ])
                    ->label('Status'),
                Tables\Columns\TextColumn::make('current_passenger_capacity')->label('Capacity'),
                Tables\Columns\TextColumn::make('dispatch_type.name')->label('Type'),
                Tables\Columns\TextColumn::make('main_dispatch_route.dispatch_route_name')->label('Route'),
                Tables\Columns\TextColumn::make('schedule')->dateTime('h:i A M j, Y')->sortable(),

            ])
            ->prependActions([
                Tables\Actions\ButtonAction::make('book')
                    ->label('Walk-in')
                    ->action(function (Dispatch $record, array $data) {
                        $passenger = User::firstWhere('email', 'walkinpassenger@gmail.com');
                        $route = $data['booking_dispatch_route_id'] ?? $record->dispatch_route_id;
                        Booking::create([
                            'transaction_id' => Str::uuid(),
                            'user_id' => $passenger->id,
                            'dispatch_id' => $record->id,
                            'dispatch_route_id' => $route,
                        ]);
                        Filament::notify('success', 'Walk-in passenger booked.');
                    })
                    ->form(function ($record) {
                        if ($record->available_dispatch_routes()->count()) {
                            return [
                                Forms\Components\Select::make('booking_dispatch_route_id')
                                    ->options(function ($record) {
                                        $routes = $record
                                            ->available_dispatch_routes
                                            ->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])
                                            ->toArray();
                                        $routes[$record->main_dispatch_route->id] = $record->main_dispatch_route->dispatch_route_name;
                                        return $routes;
                                    })
                                    ->default(function ($record) {
                                        return $record->dispatch_route_id;
                                    })
                                    ->label('Route')
                                    ->required()
                            ];
                        }
                        return [];
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-ticket')
                    ->color('warning')
                    ->hidden(fn ($record) => $record->status == Dispatch::DEPARTED || $record->passengers_count >= $record->bus_unit->passenger_capacity),
                Tables\Actions\LinkAction::make('depart')
                    ->action(function (Dispatch $record) {
                        if ($record->status != Dispatch::DEPARTED)
                            $record->update(['status' => Dispatch::DEPARTED]);
                        Filament::notify('success', 'Bus has departed.');
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->hidden(fn ($record) => $record->status == Dispatch::DEPARTED),
            ])
            ->prependBulkActions([
                Tables\Actions\BulkAction::make('depart')
                    ->action(fn (Collection $records) => $records->toQuery()->update([
                        'status' => Dispatch::DEPARTED,
                    ]))
                    ->requiresConfirmation()
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success'),
            ])

            ->filters([
                MultiSelectFilter::make('dispatch_route_id')
                    ->options(function () {
                        return DispatchRoute::get()->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray();
                    })
                    ->label('Route'),
                SelectFilter::make('status')->options([
                    Dispatch::AVAILABLE => 'Available',
                    Dispatch::DEPARTED => 'Departed'
                ])->default(Dispatch::AVAILABLE),
                Filter::make('schedule')->form([
                    Forms\Components\DatePicker::make('schedule_from'),
                    Forms\Components\DatePicker::make('schedule_until'),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['schedule_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('schedule', '>=', $date),
                        )
                        ->when(
                            $data['schedule_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('schedule', '<=', $date),
                        );
                })
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
            'index' => Pages\ListDispatches::route('/'),
            'create' => Pages\CreateDispatch::route('/create'),
            'edit' => Pages\EditDispatch::route('/{record}/edit'),
        ];
    }
}
