<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'nama_konser' => 'Konser Musik One Direction',
                'category' => 'musik',
                'harga_per_tiket' => 700000,
                'description' => 'Konser musik One Direction dengan performa luar biasa.',
                'image' => 'null',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'nama_konser' => 'Konser Musik Justin Bieber',
                'category' => 'musik',
                'harga_per_tiket' => 750000,
                'description' => 'Konser musik Justin Bieber dengan musik yang populer.',
                'image' => 'null',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_konser' => 'Festival Seni dan Budaya',
                'category' => 'festival',
                'harga_per_tiket' => 30000,
                'description' => 'Festival seni dan budaya dengan pertunjukan tari, musik, dan pameran seni.',
                'image' => 'art_festival.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_konser' => 'Seminar Teknologi',
                'category' => 'seminar',
                'harga_per_tiket' => 100000,
                'description' => 'Seminar teknologi dengan pembicara ahli di bidangnya.',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
