<?php

namespace Database\Factories;

use App\Models\Dispatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $d = Dispatch::inRandomOrder()->first();
        return [
            'transaction_id' => $this->faker->uuid,
            'user_id' => User::factory(),
            'dispatch_id' => $d?->id ?? 1,
            'dispatch_route_id' => $d->dispatch_route_id,
            'status' => $this->faker->numberBetween(1, 2),
        ];
    }
}
