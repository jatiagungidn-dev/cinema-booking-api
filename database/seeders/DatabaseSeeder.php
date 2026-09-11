<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cinema = Cinema::create([
            'name' => 'Cinema Booking Central',
            'address' => 'Jl. Cinema No. 1',
            'city' => 'Surabaya',
        ]);

        $studio = $cinema->studios()->create([
            'name' => 'Studio 1',
            'capacity' => 50,
        ]);

        foreach (range('A', 'E') as $row) {
            foreach (range(1, 10) as $number) {
                $studio->seats()->create([
                    'row' => $row,
                    'number' => $number,
                ]);
            }
        }

        Movie::create([
            'title' => 'Interstellar',
            'description' => 'A science fiction movie about space and time.',
            'duration_minutes' => 169,
            'release_date' => '2014-11-07',
        ]);

        Movie::create([
            'title' => 'Resident Evil',
            'description' => 'A survival horror movie.',
            'duration_minutes' => 110,
            'release_date' => '2026-09-01',
        ]);
    }
}
