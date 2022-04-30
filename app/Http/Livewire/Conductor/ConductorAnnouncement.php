<?php

namespace App\Http\Livewire\Conductor;

use App\Models\Announcement;
use Livewire\Component;

class ConductorAnnouncement extends Component
{
    public Announcement $announcement;

    public function render()
    {
        return view('livewire.conductor.conductor-announcement');
    }
}
