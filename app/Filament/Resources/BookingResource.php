<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use Filament\Resources\Form;
use App\Models\DispatchRoute;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\BusUnit;
use App\Models\Dispatch;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Dispatching';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_id')->label('Transaction ID')->searchable(),
                Tables\Columns\TextColumn::make('passenger.name')->searchable(),
                Tables\Columns\TextColumn::make('dispatch.bus_unit.full_description')->label('Bus'),
                Tables\Columns\TextColumn::make('dispatch_route.dispatch_route_name')->label('Route'),
                Tables\Columns\BadgeColumn::make('status_name')
                    ->colors([
                        'danger' => 'REJECTED',
                        'warning' => 'PENDING',
                        'success' => 'APPROVED',
                    ])
                    ->label('Status'),
                Tables\Columns\TextColumn::make('dispatch.schedule')
                    ->label('Schedule')
                    ->sortable()
                    ->dateTime('h:i A M d, Y'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked at')
                    ->dateTime('h:i A M d, Y'),
                Tables\Columns\TextColumn::make('dispatch_route.fare')->money('php', shouldConvert: true)->label('Fare'),
                Tables\Columns\ImageColumn::make('proof_of_payment')
                    ->url(fn ($record) => '/storage' . $record->proof_of_payment)
                    ->label('Proof of Payment'),
                Tables\Columns\TextColumn::make('reference_number')->searchable()->label('Reference Number'),
            ])
            ->defaultSort('dispatch.schedule')
            ->prependActions([
                Tables\Actions\ButtonAction::make('manage')
                    ->requiresConfirmation()
                    ->action(function (Booking $record, array $data) {
                        $record->update([
                            'status' => $data['status'],
                            'remarks' => $data['remarks']
                        ]);
                        Filament::notify('success', 'Booking approved.');
                    })
                    ->color('warning')
                    ->icon('heroicon-o-adjustments')
                    ->form([
                        Forms\Components\Radio::make('status')->options([
                            Booking::PENDING => 'Pending',
                            Booking::APPROVED => 'Approve',
                            Booking::REJECTED => 'Reject'
                        ])
                            ->default(Booking::APPROVED),
                        Forms\Components\Textarea::make('remarks'),
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    Booking::PENDING => 'Pending',
                    Booking::APPROVED => 'Approved',
                    Booking::REJECTED => 'Rejected',
                    Booking::CLAIMED => 'Claimed',
                ])->label('Booking Status'),
                Filter::make('dispatch_status_filter')->form([
                    Forms\Components\Select::make('dispatch_status')->options([
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
                MultiSelectFilter::make('bus_unit_id')
                    ->options(function () {
                        return BusUnit::get()->mapWithKeys(fn ($bu) => [$bu->id => $bu->full_description])->toArray();
                    })
                    ->column('dispatch.bus_unit_id')
                    ->label('Bus Unit'),
                MultiSelectFilter::make('dispatch_route_id')
                    ->options(function () {
                        return DispatchRoute::get()->mapWithKeys(fn ($dr) => [$dr->id => $dr->dispatch_route_name])->toArray();
                    })
                    ->label('Route'),
                Filter::make('schedule')->form([
                    Forms\Components\DatePicker::make('schedule_from'),
                    Forms\Components\DatePicker::make('schedule_until'),
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
            'index' => Pages\ListBookings::route('/'),
        ];
    }
}
