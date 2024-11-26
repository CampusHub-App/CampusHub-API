<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $events = DB::table('events')->pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            DB::table('registrations')->insert([
                'id' => Str::random(4),
                'user_id' => $users[array_rand($users)],
                'event_id' => $events[array_rand($events)],
                'is_cancelled' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}