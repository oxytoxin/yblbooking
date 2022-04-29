<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusUnit extends Model
{
    use HasFactory;

    protected $appends = ['bus_class_description', 'full_description'];
    protected $with = ['bus_class'];

    public function busClassDescription(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->bus_class->description,
        );
    }

    public function fullDescription(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "{$this->code} - {$this->bus_class_description} ({$this->passenger_capacity} passengers)",
        );
    }

    public function bus_class()
    {
        return $this->belongsTo(BusClass::class);
    }
}
