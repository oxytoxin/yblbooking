<?php

namespace App\Http\Livewire\Conductor;

use App\Models\Booking;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ConductorViewBooking extends Component
{
    use LivewireAlert;

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

        $this->alert('success', 'Booking claimed successfully by passenger.');
    }
}
