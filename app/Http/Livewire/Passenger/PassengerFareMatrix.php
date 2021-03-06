<?php

namespace App\Http\Livewire\Passenger;

use App\Models\DispatchRoute;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class PassengerFareMatrix extends Component
{

    public $tableSearchQuery = '';


    public function render()
    {
        $dispatch_routes = DispatchRoute::query()
            ->when($this->tableSearchQuery, function (Builder $query) {
                $query
                    ->whereRelation('origin', 'name', 'like', "%{$this->tableSearchQuery}%")
                    ->orWhereRelation('destination', 'name', 'like', "%{$this->tableSearchQuery}%");
            })
            ->get();
        return view('livewire.passenger.passenger-fare-matrix', [
            'dispatch_routes' => $dispatch_routes,
        ]);
    }
}
