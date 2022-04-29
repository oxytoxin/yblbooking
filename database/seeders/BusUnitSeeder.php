<?php

namespace Database\Seeders;

use App\Models\BusClass;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $bus_classes = BusClass::get();
        foreach ($bus_classes as $key => $bus_class) {
            for ($i = 0; $i < 25; $i++) {
                $bus_class->bus_units()->create([
                    'code' => strtoupper($faker->bothify('?###')),
                    'platenumber' => strtoupper($faker->bothify('???###')),
                    'passenger_capacity' => 50,
                ]);
            }
        }
    }
}
