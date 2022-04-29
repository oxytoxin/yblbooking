<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchRoute extends Model
{
    use HasFactory;

    protected $appends = ['dispatch_route_name'];
    protected $with = ['origin', 'destination'];

    public function dispatchRouteName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->origin->name . ' - ' . $this->destination->name,
        );
    }

    public function fare(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function origin()
    {
        return $this->belongsTo(Terminal::class, 'from_terminal');
    }

    public function destination()
    {
        return $this->belongsTo(Terminal::class, 'to_terminal');
    }

    public function dispatches()
    {
        return $this->belongsToMany(Dispatch::class);
    }
}
