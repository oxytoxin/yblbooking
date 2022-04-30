<?php

namespace App\Http\Livewire\Conductor;

use App\Models\Booking;
use Livewire\Component;

class ConductorViewBooking extends Component
{
    public Booking $booking;

    public function render()
    {
        return view('livewire.conductor.conductor-view-booking');
    }

    public function claimBooking()
    {
        $this->booking->update([
            'status' => Booking::CLAIMED
        ]);
    }
}
