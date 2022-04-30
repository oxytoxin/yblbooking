<?php

namespace App\Http\Livewire\Passenger;

use App\Models\Booking;
use Livewire\Component;
use App\Models\Dispatch;
use Akaunting\Money\Money;
use App\Models\BusUnit;
use Illuminate\Support\Str;
use App\Models\DispatchRoute;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Placeholder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PassengerDispatches extends Component implements HasTable
{
    use InteractsWithTable, LivewireAlert;

    public function render()
    {

        return view('livewire.passenger.passenger-dispatches');
    }

    protected function getTableQuery(): Builder
    {
        return Dispatch::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('current_passenger_capacity')->sortable(['passengers_count'])->label('Capacity'),
            TextColumn::make('main_dispatch_route.fare')->money('php', true)->sortable()->label('Fare'),
            TextColumn::make('schedule')->dateTime('h:i A M d, Y')->sortable(),
            TextColumn::make('bus_unit.full_description')->label('Bus Unit'),
            TextColumn::make('dispatch_type.name')->label('Type'),
            TextColumn::make('main_dispatch_route.dispatch_route_name')->label('Route'),
        ];
    }

    public function getTableFilters()
    {
        return [
            MultiSelectFilter::make('dispatch_route_id')
                ->options(function () {
                    return DispatchRoute::get()->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray();
                })
                ->label('Route'),
            MultiSelectFilter::make('bus_unit_id')
                ->options(function () {
                    return BusUnit::get()->mapWithKeys(fn ($bu) => [$bu->id => $bu->full_description])->toArray();
                })->column('bus_unit.id')
                ->label('Bus Unit'),
            SelectFilter::make('status')->options([
                Dispatch::AVAILABLE => 'Available',
                Dispatch::DEPARTED => 'Departed'
            ])->default(Dispatch::AVAILABLE),
            Filter::make('schedule')->form([
                DatePicker::make('schedule_from'),
                DatePicker::make('schedule_until'),
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
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ButtonAction::make('Book')
                ->icon('heroicon-o-ticket')
                ->action(function ($record, $data) {
                    $route = $data['booking_dispatch_route_id'] ?? $record->dispatch_route_id;
                    if ($record->current_passenger_capacity < $record->bus_unit->passenger_capacity) {
                        Booking::create([
                            'transaction_id' => \Str::uuid(),
                            'user_id' => auth()->id(),
                            'dispatch_id' => $record->id,
                            'dispatch_route_id' => $route,
                            'reference_number' => $data['reference_number'],
                            'proof_of_payment' => $data['proof_of_payment'],
                        ]);
                        $this->alert('success', 'Dispatch successfully booked.');
                    } else {
                        $this->alert('error', 'This dispatch is fully booked.');
                    }
                })
                ->hidden(fn ($record) => Dispatch::DEPARTED == $record->status)
                ->form(function ($record) {
                    if ($record->available_dispatch_routes()->count()) {
                        return [
                            Placeholder::make('payment_info_gcash')->label('G-cash:')
                                ->content('09389632164'),
                            Placeholder::make('payment_info_bank')->label('Landbank Fund Transfer:')
                                ->content('3886032713'),
                            Placeholder::make('fare')
                                ->content(fn ($get) => Money::PHP(DispatchRoute::find($get('booking_dispatch_route_id'))->fare, true)),
                            Select::make('booking_dispatch_route_id')
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
                                ->reactive()
                                ->required(),
                            TextInput::make('reference_number')->required(),
                            FileUpload::make('proof_of_payment')
                                ->image()
                                ->directory('proof_of_payments')
                                ->required()
                        ];
                    }
                    return [
                        Placeholder::make('payment_info_gcash')->label('G-cash:')
                            ->content('09389632164'),
                        Placeholder::make('payment_info_bank')->label('Landbank Fund Transfer:')
                            ->content('3886032713'),
                        Placeholder::make('fare')
                            ->content(fn ($record) => Money::PHP($record->main_dispatch_route->fare, true)),
                        TextInput::make('reference_number')->required(),
                        FileUpload::make('proof_of_payment')
                            ->image()
                            ->directory('proof_of_payments')
                            ->required()
                    ];
                })
                ->requiresConfirmation()
                ->hidden(fn ($record) => $record->status == Dispatch::DEPARTED || $record->passengers_count >= $record->bus_unit->passenger_capacity)
        ];
    }
}
