<?php

namespace App\Http\Livewire\Passenger;

use App\Models\Booking;
use Livewire\Component;

class PassengerViewBooking extends Component
{
    public Booking $booking;

    public function render()
    {
        return view('livewire.passenger.passenger-view-booking');
    }
}
