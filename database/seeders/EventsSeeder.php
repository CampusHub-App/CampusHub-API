<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = DB::table('categories')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $isOffline = (bool)rand(0, 1);

            DB::table('events')->insert([
                'id' => Str::random(12),
                'kategori_id' => $categories[array_rand($categories)],
                'user_id' => $users[array_rand($users)],
                'judul' => fake()->sentence(3),
                'datetime' => fake()->dateTimeBetween('+1 week', '+3 months'),
                'foto_event' => null,
                'foto_pembicara' => null,
                'pembicara' => fake()->name(),
                'role' => fake()->jobTitle(),
                'deskripsi' => fake()->paragraph(5),
                'available_slot' => rand(50, 200),
                'is_offline' => $isOffline,
                'tempat' => $isOffline ? fake()->address() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
