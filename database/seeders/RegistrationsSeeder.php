<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = [

            ['id' => 'a1B2', 'user_id' => '3pQoYm', 'event_id' => '6wG2yTL07N7Q', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'c3D4', 'user_id' => '3pQoYm', 'event_id' => 'EArzlqOiHqCy', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'e5F6', 'user_id' => '3pQoYm', 'event_id' => 'IhnQLC0AbQBt', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'g7H8', 'user_id' => '3pQoYm', 'event_id' => 'KudupxXgSCfO', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'i9J0', 'user_id' => '3pQoYm', 'event_id' => 'mQzJaDSFaEdk', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'k1L2', 'user_id' => '3pQoYm', 'event_id' => 'nZLoL9zwHCHQ', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'm3N4', 'user_id' => '3pQoYm', 'event_id' => 'oRgtmFSDO6aa', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'o5P6', 'user_id' => '3pQoYm', 'event_id' => 'R2FUAsX0Thil', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'q7R8', 'user_id' => '3pQoYm', 'event_id' => 'se9o9UmY3mob', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 's9T0', 'user_id' => '5qRmYk', 'event_id' => '6wG2yTL07N7Q', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'u1V2', 'user_id' => '5qRmYk', 'event_id' => 'EArzlqOiHqCy', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'w3X4', 'user_id' => '5qRmYk', 'event_id' => 'IhnQLC0AbQBt', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'y5Z6', 'user_id' => '5qRmYk', 'event_id' => 'KudupxXgSCfO', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'a7B8', 'user_id' => '5qRmYk', 'event_id' => 'mQzJaDSFaEdk', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'c9D0', 'user_id' => '5qRmYk', 'event_id' => 'nZLoL9zwHCHQ', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'e1F2', 'user_id' => '5qRmYk', 'event_id' => 'oRgtmFSDO6aa', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'g3H4', 'user_id' => '5qRmYk', 'event_id' => 'R2FUAsX0Thil', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'i5J6', 'user_id' => '5qRmYk', 'event_id' => 'se9o9UmY3mob', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'k7L8', 'user_id' => '6pSlXj', 'event_id' => '6wG2yTL07N7Q', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'm9N0', 'user_id' => '6pSlXj', 'event_id' => 'EArzlqOiHqCy', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'o1P2', 'user_id' => '6pSlXj', 'event_id' => 'IhnQLC0AbQBt', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'q3R4', 'user_id' => '6pSlXj', 'event_id' => 'KudupxXgSCfO', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 's5T6', 'user_id' => '6pSlXj', 'event_id' => 'mQzJaDSFaEdk', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'u7V8', 'user_id' => '6pSlXj', 'event_id' => 'nZLoL9zwHCHQ', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'w9X0', 'user_id' => '6pSlXj', 'event_id' => 'oRgtmFSDO6aa', 'is_cancelled' => 0, 'is_attended' => 1],
            ['id' => 'y1Z2', 'user_id' => '6pSlXj', 'event_id' => 'R2FUAsX0Thil', 'is_cancelled' => 1, 'is_attended' => 0],
            ['id' => 'a3B4', 'user_id' => '6pSlXj', 'event_id' => 'se9o9UmY3mob', 'is_cancelled' => 0, 'is_attended' => 1],

        ];

        foreach ($registrations as &$registration) {
            $registration['created_at'] = now();
            $registration['updated_at'] = now();
        }

        DB::table('registrations')->insert($registrations);

    }
}
