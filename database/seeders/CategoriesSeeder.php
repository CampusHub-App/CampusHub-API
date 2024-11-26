<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Webinar', 'Seminar', 'Kuliah Tamu', 'Workshop', 'Sertifikasi'];

        foreach ($categories as $kategori) {
            DB::table('categories')->insert([
                'kategori' => $kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}