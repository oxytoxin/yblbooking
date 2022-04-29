<?php

namespace App\Http\Livewire\Passenger;

use Akaunting\Money\Money;
use App\Models\Booking;
use App\Models\Dispatch;
use App\Models\DispatchRoute;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PassengerDispatches extends Component implements HasTable
{
    use InteractsWithTable, LivewireAlert;

    public function render()
    {

        return view('livewire.passenger.passenger-dispatches');
    }

    public function showAlert()
    {
        $this->alert('success', 'Dispatch successfully booked.');
    }


    protected function getTableQuery(): Builder
    {
        return Dispatch::query()->where('status', Dispatch::AVAILABLE);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('current_passenger_capacity')->label('Capacity'),
            TextColumn::make('dispatch_type.name')->label('Type'),
            TextColumn::make('bus_unit.full_description')->label('Bus Unit'),
            TextColumn::make('main_dispatch_route.dispatch_route_name')->label('Route'),
            TextColumn::make('schedule')->dateTime('h:i A M d, Y'),
            TextColumn::make('main_dispatch_route.fare')->money('php', true)->label('Fare'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ButtonAction::make('Book')
                ->icon('heroicon-o-ticket')
                ->action(function ($record, $data) {
                    $route = $data['booking_dispatch_route_id'] ?? $record->dispatch_route_id;
                    Booking::create([
                        'transaction_id' => \Str::uuid(),
                        'user_id' => auth()->id(),
                        'dispatch_id' => $record->id,
                        'dispatch_route_id' => $route,
                        'reference_number' => $data['reference_number'],
                        'proof_of_payment' => '/storage/' . $data['proof_of_payment'],
                    ]);
                    $this->alert('success', 'Dispatch successfully booked.');
                })
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
