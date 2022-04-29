<?php

namespace App\Http\Livewire\Passenger;

use App\Models\DispatchRoute;
use Livewire\Component;

class PassengerFareMatrix extends Component
{


    public function render()
    {
        return view('livewire.passenger.passenger-fare-matrix', [
            'dispatch_routes' => DispatchRoute::get(),
        ]);
    }
}
