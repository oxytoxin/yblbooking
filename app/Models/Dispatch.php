<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;
    const AVAILABLE = 1;
    const DEPARTED = 2;

    protected $appends = ['current_passenger_capacity'];

    protected $with = ['bus_unit'];

    protected $casts = [
        'schedule' => 'immutable_datetime'
    ];

    public function main_dispatch_route()
    {
        return $this->belongsTo(DispatchRoute::class, 'dispatch_route_id');
    }

    public function available_dispatch_routes()
    {
        return $this->belongsToMany(DispatchRoute::class);
    }

    public function dispatch_type()
    {
        return $this->belongsTo(DispatchType::class);
    }

    public function statusName(): Attribute
    {
        return Attribute::make(get: function ($value) {
            return match ($this->status) {
                static::AVAILABLE => 'Available',
                static::DEPARTED => 'Departed',
            };
        });
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bus_unit()
    {
        return $this->belongsTo(BusUnit::class);
    }

    public function currentPassengerCapacity(): Attribute
    {
        return new Attribute(get: fn ($value) => $this->passengers_count . '/' . $this->bus_unit->passenger_capacity);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('passengerCount', function ($builder) {
            $builder->withCount('bookings as passengers_count');
        });
    }
}
