<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusClass extends Model
{
    use HasFactory;

    public function bus_units()
    {
        return $this->hasMany(BusUnit::class);
    }
}
