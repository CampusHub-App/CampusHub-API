<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'id' => Str::random(6),
                'fullname' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => bcrypt('password'), // Use a default password
                'photo' => null,
                'nomor_telepon' => fake()->unique()->phoneNumber(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}