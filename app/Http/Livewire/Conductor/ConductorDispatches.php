<?php

namespace App\Http\Livewire\Conductor;

use Livewire\Component;
use App\Models\Dispatch;
use App\Models\DispatchRoute;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\LinkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class ConductorDispatches extends Component implements HasTable
{
    use InteractsWithTable, LivewireAlert;

    protected function getTableQuery(): Builder
    {
        return Dispatch::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('bus_unit.code')->searchable()->label('Bus'),
            BadgeColumn::make('status_name')
                ->colors([
                    'warning' => 'Departed',
                    'success' => 'Available',
                ])
                ->label('Status'),
            TextColumn::make('current_passenger_capacity')->label('Capacity'),
            TextColumn::make('dispatch_type.name')->label('Type'),
            TextColumn::make('main_dispatch_route.dispatch_route_name')->label('Route'),
            TextColumn::make('schedule')->dateTime('h:i A M j, Y')->sortable(),
        ];
    }

    public function getTableActions()
    {
        return [
            LinkAction::make('depart')
                ->action(function (Dispatch $record) {
                    if ($record->status != Dispatch::DEPARTED)
                        $record->update(['status' => Dispatch::DEPARTED]);
                    Filament::notify('success', 'Bus has departed.');
                })
                ->requiresConfirmation()
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->hidden(fn ($record) => $record->status == Dispatch::DEPARTED),
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
    public function render()
    {
        return view('livewire.conductor.conductor-dispatches');
    }
}
