<?php

namespace App\Http\Livewire\Conductor;

use Closure;
use App\Models\Booking;
use Livewire\Component;
use App\Models\Dispatch;
use App\Models\DispatchRoute;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\MultiSelectFilter;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ConductorBookings extends Component  implements HasTable
{
    use InteractsWithTable, LivewireAlert;

    protected function getTableQuery(): Builder
    {
        return Booking::query();
    }


    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Booking $record): string => route('conductor.view_booking', ['booking' => $record]);
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

    public function getTableActions()
    {
        return [
            ButtonAction::make('claim')
                ->action(function ($record) {
                    $record->update([
                        'status' => Booking::CLAIMED
                    ]);
                    $this->alert('success', 'Booking was claimed successfully for passenger.');
                })
                ->requiresConfirmation()
                ->color('success'),
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
                ->default(Booking::APPROVED)
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
        return view('livewire.conductor.conductor-bookings');
    }
}
