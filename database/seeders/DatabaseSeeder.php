<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
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

        $interstellar = Movie::create([
            'title' => 'Interstellar',
            'description' => 'A science fiction movie about space and time.',
            'duration_minutes' => 169,
            'release_date' => '2014-11-07',
        ]);

        $residentEvil = Movie::create([
            'title' => 'Resident Evil',
            'description' => 'A survival horror movie.',
            'duration_minutes' => 110,
            'release_date' => '2026-09-01',
        ]);

        Showtime::create([
            'movie_id' => $interstellar->id,
            'studio_id' => $studio->id,
            'starts_at' => '2026-09-15 13:00:00',
            'ends_at' => '2026-09-15 15:49:00',
            'price' => 50000,
        ]);

        Showtime::create([
            'movie_id' => $interstellar->id,
            'studio_id' => $studio->id,
            'starts_at' => '2026-09-15 19:00:00',
            'ends_at' => '2026-09-15 21:49:00',
            'price' => 50000,
        ]);

        Showtime::create([
            'movie_id' => $residentEvil->id,
            'studio_id' => $studio->id,
            'starts_at' => '2026-09-15 19:00:00',
            'ends_at' => '2026-09-15 20:50:00',
            'price' => 55000,
        ]);
    }
}
