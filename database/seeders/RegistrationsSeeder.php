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

            ['id' => 'a1B2', 'user_id' => '3pQoYm', 'event_id' => '6wG2yTL07N7Q', 'status' => 'registered'],
            ['id' => 'c3D4', 'user_id' => '3pQoYm', 'event_id' => 'EArzlqOiHqCy', 'status' => 'cancelled'],
            ['id' => 'e5F6', 'user_id' => '3pQoYm', 'event_id' => 'IhnQLC0AbQBt', 'status' => 'registered'],
            ['id' => 'g7H8', 'user_id' => '3pQoYm', 'event_id' => 'KudupxXgSCfO', 'status' => 'cancelled'],
            ['id' => 'i9J0', 'user_id' => '3pQoYm', 'event_id' => 'mQzJaDSFaEdk', 'status' => 'registered'],
            ['id' => 'k1L2', 'user_id' => '3pQoYm', 'event_id' => 'nZLoL9zwHCHQ', 'status' => 'cancelled'],
            ['id' => 'm3N4', 'user_id' => '3pQoYm', 'event_id' => 'oRgtmFSDO6aa', 'status' => 'registered'],
            ['id' => 'o5P6', 'user_id' => '3pQoYm', 'event_id' => 'R2FUAsX0Thil', 'status' => 'cancelled'],
            ['id' => 'q7R8', 'user_id' => '3pQoYm', 'event_id' => 'se9o9UmY3mob', 'status' => 'registered'],
            ['id' => 's9T0', 'user_id' => '5qRmYk', 'event_id' => '6wG2yTL07N7Q', 'status' => 'cancelled'],
            ['id' => 'u1V2', 'user_id' => '5qRmYk', 'event_id' => 'EArzlqOiHqCy', 'status' => 'registered'],
            ['id' => 'w3X4', 'user_id' => '5qRmYk', 'event_id' => 'IhnQLC0AbQBt', 'status' => 'cancelled'],
            ['id' => 'y5Z6', 'user_id' => '5qRmYk', 'event_id' => 'KudupxXgSCfO', 'status' => 'registered'],
            ['id' => 'a7B8', 'user_id' => '5qRmYk', 'event_id' => 'mQzJaDSFaEdk', 'status' => 'cancelled'],
            ['id' => 'c9D0', 'user_id' => '5qRmYk', 'event_id' => 'nZLoL9zwHCHQ', 'status' => 'registered'],
            ['id' => 'e1F2', 'user_id' => '5qRmYk', 'event_id' => 'oRgtmFSDO6aa', 'status' => 'cancelled'],
            ['id' => 'g3H4', 'user_id' => '5qRmYk', 'event_id' => 'R2FUAsX0Thil', 'status' => 'registered'],
            ['id' => 'i5J6', 'user_id' => '5qRmYk', 'event_id' => 'se9o9UmY3mob', 'status' => 'cancelled'],
            ['id' => 'k7L8', 'user_id' => '6pSlXj', 'event_id' => '6wG2yTL07N7Q', 'status' => 'attended'],
            ['id' => 'm9N0', 'user_id' => '6pSlXj', 'event_id' => 'EArzlqOiHqCy', 'status' => 'absent'],
            ['id' => 'o1P2', 'user_id' => '6pSlXj', 'event_id' => 'IhnQLC0AbQBt', 'status' => 'attended'],
            ['id' => 'q3R4', 'user_id' => '6pSlXj', 'event_id' => 'KudupxXgSCfO', 'status' => 'absent'],
            ['id' => 's5T6', 'user_id' => '6pSlXj', 'event_id' => 'mQzJaDSFaEdk', 'status' => 'attended'],
            ['id' => 'u7V8', 'user_id' => '6pSlXj', 'event_id' => 'nZLoL9zwHCHQ', 'status' => 'absent'],
            ['id' => 'w9X0', 'user_id' => '6pSlXj', 'event_id' => 'oRgtmFSDO6aa', 'status' => 'attended'],
            ['id' => 'y1Z2', 'user_id' => '6pSlXj', 'event_id' => 'R2FUAsX0Thil', 'status' => 'absent'],
            ['id' => 'a3B4', 'user_id' => '6pSlXj', 'event_id' => 'se9o9UmY3mob', 'status' => 'attended'],

        ];

        foreach ($registrations as &$registration) {
            $registration['created_at'] = now();
            $registration['updated_at'] = now();
        }

        DB::table('registrations')->insert($registrations);

    }
}
