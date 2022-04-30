<?php

namespace App\Http\Livewire\Passenger;

use App\Models\Booking;
use App\Models\BusUnit;
use Livewire\Component;
use App\Models\Dispatch;
use App\Models\DispatchRoute;
use Closure;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class PassengerBookings extends Component implements HasTable
{
    use InteractsWithTable, LivewireAlert;

    protected function getTableQuery(): Builder
    {
        return Booking::query()->where('user_id', auth()->id());
    }


    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Booking $record): string => route('passenger.view_booking', ['booking' => $record]);
    }

    protected function getTableColumns(): array
    {
        return [
            BadgeColumn::make('status_name')
                ->colors([
                    'danger' => 'REJECTED',
                    'warning' => 'PENDING',
                    'success' => 'APPROVED',
                    'primary' => 'CLAIMED',
                ])
                ->label('Status'),
            TextColumn::make('dispatch_route.fare')->money('php', shouldConvert: true)->label('Fare'),
            ImageColumn::make('proof_of_payment')
                ->url(fn ($record) => $record->proof_of_payment ? ('/storage/' . $record->proof_of_payment) : null)
                ->label('Proof of Payment'),
            TextColumn::make('reference_number')->searchable()->label('Reference Number'),
            TextColumn::make('transaction_id')->label('Transaction ID')->searchable(),
            TextColumn::make('passenger.name')->searchable(),
            TextColumn::make('dispatch.bus_unit.full_description')->label('Bus'),
            TextColumn::make('dispatch_route.dispatch_route_name')->label('Route'),
            TextColumn::make('dispatch.schedule')
                ->label('Schedule')
                ->sortable()
                ->dateTime('h:i A M d, Y'),
            TextColumn::make('created_at')
                ->label('Booked at')
                ->dateTime('h:i A M d, Y'),
        ];
    }

    public function getTableFilters()
    {
        return [
            SelectFilter::make('status')->options([
                Booking::PENDING => 'Pending',
                Booking::APPROVED => 'Approved',
                Booking::REJECTED => 'Rejected',
                Booking::CLAIMED => 'Claimed',
            ])
                ->label('Booking Status'),
            Filter::make('dispatch_status_filter')->form([
                Select::make('dispatch_status')->options([
                    0 => 'All',
                    Dispatch::AVAILABLE => 'Available',
                    Dispatch::DEPARTED => 'Departed',
                ])->label('Dispatch Status')->default(0),
            ])->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['dispatch_status'],
                        fn (Builder $query, $status): Builder => $query->whereRelation('dispatch', function (Builder $query) use ($status) {
                            $query->where('status', $status)->orderBy('schedule');
                        }),
                    );
            }),
            MultiSelectFilter::make('dispatch_route_id')
                ->options(function () {
                    return DispatchRoute::get()->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray();
                })
                ->label('Route'),
            Filter::make('schedule')->form([
                DatePicker::make('schedule_from'),
                DatePicker::make('schedule_until'),
            ])->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['schedule_from'],
                        fn (Builder $query, $date): Builder => $query->whereRelation('dispatch', function (Builder $query) use ($date) {
                            $query->whereDate('schedule', '>=', $date);
                        }),
                    )
                    ->when(
                        $data['schedule_until'],
                        fn (Builder $query, $date): Builder => $query->whereRelation('dispatch', function (Builder $query) use ($date) {
                            $query->whereDate('schedule', '<=', $date);
                        }),
                    );
            })
        ];
    }

    public function render()
    {
        return view('livewire.passenger.passenger-bookings');
    }
}
