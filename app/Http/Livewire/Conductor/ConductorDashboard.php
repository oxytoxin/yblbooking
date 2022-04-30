<?php

namespace App\Http\Livewire\Conductor;

use App\Models\Announcement;
use App\Models\Role;
use Livewire\Component;

class ConductorDashboard extends Component
{
    public function render()
    {
        return view('livewire.conductor.conductor-dashboard', [
            'announcements' => Announcement::where('role_id', Role::CONDUCTOR)->get()
        ]);
    }
}
