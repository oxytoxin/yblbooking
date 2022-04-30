<?php

namespace App\Http\Livewire\Passenger;

use App\Models\Announcement;
use App\Models\Role;
use Livewire\Component;

class PassengerDashboard extends Component
{
    public function render()
    {
        return view('livewire.passenger.passenger-dashboard', [
            'announcements' => Announcement::where('role_id', Role::PASSENGER)->get()
        ]);
    }
}
