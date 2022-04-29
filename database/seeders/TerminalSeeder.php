<?php

namespace Database\Seeders;

use App\Models\Terminal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Terminal::create([
            'route' => 97,
            'code' => 'KOR-INTEG',
            'name' => 'Koronadal Integrated Terminal',
            'description' => 'Bus Terminal',
        ]);
        Terminal::create([
            'route' => 97,
            'code' => 'KOR-YBL',
            'name' => 'Koronadal Yellow Bus Terminal',
            'description' => 'Bus Terminal',
        ]);
        Terminal::create([
            'route' => 143,
            'code' => 'GSC-BUL',
            'name' => 'General Santos Bulaong',
            'description' => 'Bus Terminal',
        ]);
    }
}
