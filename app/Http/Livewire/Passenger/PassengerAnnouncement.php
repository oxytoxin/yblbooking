<?php

namespace App\Http\Livewire\Passenger;

use App\Models\Announcement;
use Livewire\Component;

class PassengerAnnouncement extends Component
{
    public Announcement $announcement;

    public function render()
    {
        return view('livewire.passenger.passenger-announcement');
    }
}
