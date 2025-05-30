<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $events = [
            [
                'id' => '6wG2yTL07N7Q',
                'judul' => 'Sertifikasi Cisco CCNA',
                'date' => '2024-12-11',
                'start_time' => '10:00',
                'end_time' => '15:00',
                'foto_event' => 'events/1.jpeg',
                'foto_pembicara' => 'speakers/cisco.png',
                'pembicara' => 'Cisco',
                'role' => 'Cisco Networking Academy',
                'deskripsi' => 'Disarankan sudah mengambil MK jarkom atau sejenisnya.',
                'available_slot' => 100,
                'tempat' => 'Online',
                'kategori_id' => 5,
                'user_id' => '2kNuXn',
            ],
            [
                'id' => 'nZLoL9zwHCHQ',
                'judul' => 'Workshop Metodologi Penelitian Survei Opini Publik',
                'date' => '2024-12-25',
                'start_time' => '09:00',
                'end_time' => '12:00',
                'foto_event' => 'events/2.jpg',
                'foto_pembicara' => 'speakers/ratno.jpg',
                'pembicara' => 'Ratno Sulistiyanto',
                'role' => 'CEO Indopol Survey',
                'deskripsi' => 'Memanggil para peneliti S1, S2, dan S3 untuk memperkuat skill riset survei opini publik.',
                'available_slot' => 150,
                'tempat' => 'UB Guest House',
                'kategori_id' => 4,
                'user_id' => '2kNuXn',
            ],
            [
                'id' => 'oRgtmFSDO6aa',
                'judul' => 'Hology Grand Seminar: A Glance at the Gate of Evolving Technology',
                'date' => '2024-12-10',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'foto_event' => 'events/3.jpg',
                'foto_pembicara' => 'speakers/hanif.jpg',
                'pembicara' => 'Hanif Srisubaga Alim',
                'role' => 'CEO Office FIT HUB',
                'deskripsi' => 'Membahas kunci kesuksesan dalam industri IT.',
                'available_slot' => 80,
                'tempat' => 'Auditorium Algoritma FILKOM UB',
                'kategori_id' => 2,
                'user_id' => '2kNuXn',
            ],
            [
                'id' => 'IhnQLC0AbQBt',
                'judul' => 'Praktisi Mengajar: Machine Learning in Industry',
                'date' => '2024-12-15',
                'start_time' => '10:00',
                'end_time' => '16:00',
                'foto_event' => 'events/4.png',
                'foto_pembicara' => 'speakers/yusuf.jpg',
                'pembicara' => 'Yusuf Priyo Anggodo, M.Kom',
                'role' => 'Data Scientist',
                'deskripsi' => 'Pelatihan intensif pengembangan machine learning untuk pemula hingga tingkat lanjut.',
                'available_slot' => 120,
                'kategori_id' => 3,
                'user_id' => '2kNuXn',
                'tempat' => 'Online',
            ],
            [
                'id' => 'EArzlqOiHqCy',
                'judul' => 'Computer Engineering Webinar: Embedded Systems on MATLAB & Simulink',
                'date' => '2024-12-20',
                'start_time' => '08:00',
                'end_time' => '20:00',
                'foto_event' => 'events/5.jpg',
                'foto_pembicara' => 'speakers/ian.jpg',
                'pembicara' => 'Ian Philip',
                'role' => 'MATLAB Application Engineer',
                'deskripsi' => 'Disarankan sudah menyelesaikan pelatihan daring MATLAB Onramp.',
                'available_slot' => 200,
                'kategori_id' => 1,
                'user_id' => '2kNuXn',
                'tempat' => 'Online',
            ],
            [
                'id' => 'mQzJaDSFaEdk',
                'judul' => 'Strategi Jitu Lolos Beasiswa PMDSU',
                'date' => '2024-12-12',
                'start_time' => '10:00',
                'end_time' => '15:00',
                'foto_event' => 'events/6.jpg',
                'foto_pembicara' => 'speakers/diva.jpg',
                'pembicara' => 'Dr. Diva Kurnianingtyas, S.Kom.',
                'role' => 'Alumni PMDSU Batch III',
                'deskripsi' => 'Pendidikan magister menuju doktor untuk sarjana unggul.',
                'available_slot' => 100,
                'kategori_id' => 3,
                'user_id' => '4rSpZl',
                'tempat' => 'Online',
            ],
            [
                'id' => 'se9o9UmY3mob',
                'judul' => 'Guest Lecture Series: Application to Computational Lensless Imaging',
                'date' => '2024-12-15',
                'start_time' => '09:00',
                'end_time' => '12:00',
                'foto_event' => 'events/7.jpg',
                'foto_pembicara' => 'speakers/tomoya.jpg',
                'pembicara' => 'Assoc. Prof. Tokomya Nakamura',
                'role' => 'Osaka University Lecturer',
                'deskripsi' => 'Seminar tentang pencitraan tanpa lensa komputasional dan penerapannya di berbagai bidang.',
                'available_slot' => 150,
                'kategori_id' => 3,
                'user_id' => '4rSpZl',
                'tempat' => 'Online',
            ],
            [
                'id' => 'R2FUAsX0Thil',
                'judul' => 'Special Guest Lecture: Managing Diversity in Projects',
                'date' => '2024-12-10',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'foto_event' => 'events/8.jpg',
                'foto_pembicara' => 'speakers/anna.jpg',
                'pembicara' => 'Prof Anna Y. Khodijah',
                'role' => 'Researcher in Information Systems and Strategic IT Manegement',
                'deskripsi' => 'Pelatihan untuk meningkatkan kemampuan sebagai pemimpin.',
                'available_slot' => 80,
                'kategori_id' => 3,
                'user_id' => '4rSpZl',
                'tempat' => 'Online',
            ],
            [
                'id' => 'KudupxXgSCfO',
                'judul' => 'Sharing Knowledge: Immersive Techs For Education Field',
                'date' => '2025-12-15',
                'start_time' => '10:00',
                'end_time' => '16:00',
                'foto_event' => 'events/10.jpg',
                'foto_pembicara' => 'speakers/markus.jpg',
                'pembicara' => 'Prof. Markus Santoso',
                'role' => 'University of Florida Lecturer',
                'deskripsi' => 'Mendukung inovasi dunia pendidikan dengan AR dan VR.',
                'available_slot' => 120,
                'tempat' => 'Auditorium Algoritma FILKOM UB',
                'kategori_id' => 1,
                'user_id' => '4rSpZl',
            ]
        ];

        foreach ($events as &$event) {
            $event['created_at'] = now();
            $event['updated_at'] = now();
        }

        DB::table('events')->insert($events);
    }
}