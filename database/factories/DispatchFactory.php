<?php

namespace Database\Factories;

use App\Models\BusUnit;
use App\Models\DispatchRoute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dispatch>
 */
class DispatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dispatch_route_id' => DispatchRoute::inRandomOrder()->first()->id,
            'dispatch_type_id' => 1,
            'bus_unit_id' => BusUnit::inRandomOrder()->first()->id,
            'schedule' => $this->faker->dateTimeBetween('now', '7 days'),
        ];
    }
}
