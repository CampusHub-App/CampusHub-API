<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => '2kNuXn',
                'fullname' => 'Hasbi Mizan Azzami',
                'email' => 'hasbi@gmail.com',
                'password' => bcrypt('12345678'),
                'photo' => 'https://campushub.s3.ap-southeast-3.amazonaws.com/users/hasbi.jpeg',
                'nomor_telepon' => '081234567890',
                'is_admin' => true,
            ],
            [
                'id' => '3pQoYm',
                'fullname' => 'A. Issadurrofiq Jaya Utama',
                'email' => 'rofiq@gmail.com',
                'password' => bcrypt('12345678'),
                'photo' => 'https://campushub.s3.ap-southeast-3.amazonaws.com/users/rofiq.png',
                'nomor_telepon' => '081234567891',
                'is_admin' => true,
            ],
            [
                'id' => '4rSpZl',
                'fullname' => 'Pande Gede Natha Satvika',
                'email' => 'natha@gmail.com',
                'password' => bcrypt('12345678'),
                'photo' => 'https://campushub.s3.ap-southeast-3.amazonaws.com/users/natha.jpg',
                'nomor_telepon' => '081234567892',
                'is_admin' => true,
            ],
            [
                'id' => '5qRmYk',
                'fullname' => 'Alfaril Dzaky',
                'email' => 'faril@gmail.com',
                'password' => bcrypt('12345678'),
                'photo' => 'https://campushub.s3.ap-southeast-3.amazonaws.com/users/faril.png',
                'nomor_telepon' => '081234567893',
                'is_admin' => true,
            ],
            [
                'id' => '6pSlXj',
                'fullname' => 'Muhammad Alucard',
                'email' => 'alucard@gmail.com',
                'password' => bcrypt('12345678'),
                'photo' => null,
                'nomor_telepon' => '081234567894',
                'is_admin' => false,
            ],
        ];

        foreach ($users as &$user) {
            $user['created_at'] = now();
            $user['updated_at'] = now();
        }

        DB::table('users')->insert($users);
    }
}