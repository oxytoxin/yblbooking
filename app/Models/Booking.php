<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const CLAIMED = 4;


    public function statusName(): Attribute
    {
        return new Attribute(get: fn ($value) => match ($this->status) {
            static::PENDING => 'PENDING',
            static::APPROVED => 'APPROVED',
            static::REJECTED => 'REJECTED',
            static::CLAIMED => 'CLAIMED',
        });
    }

    public function passenger()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dispatch_route()
    {
        return $this->belongsTo(DispatchRoute::class);
    }

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }
}
