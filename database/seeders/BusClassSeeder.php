<?php

namespace Database\Seeders;

use App\Models\BusClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusClass::create([
            'description' => 'Mabuhay'
        ]);
        BusClass::create([
            'description' => 'Super Deluxe'
        ]);
        BusClass::create([
            'description' => 'Executive'
        ]);
    }
}
