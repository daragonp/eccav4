<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PodCastTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('podcasts')->insert([
            [
                'id' => 1,
                'title' => 'Romanos - Introducción',
                'description' => 'Primer capítulo de la serie Romanos',
                'audio_file' => '1641336554.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:49:14'),
                'updated_at' => Carbon::parse('2022-01-04 17:49:14'),
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'title' => 'Romanos - La epístola, una luz en las tinieblas',
                'description' => 'Segundo capítulo de la serie Romanos',
                'audio_file' => '1641336650.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:50:50'),
                'updated_at' => Carbon::parse('2022-01-04 17:50:50'),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'title' => 'Romanos - Escogidos de alto valor',
                'description' => 'Tercer capítulo de la serie Romanos',
                'audio_file' => '1641336704.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:51:45'),
                'updated_at' => Carbon::parse('2022-01-04 17:51:45'),
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'title' => 'Romanos - Siervo de alguien (primera parte)',
                'description' => 'Cuarto capítulo de la serie Romanos',
                'audio_file' => '1641336745.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:52:25'),
                'updated_at' => Carbon::parse('2022-01-04 17:53:01'),
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'title' => 'Romanos - Siervo de alguien (segunda parte)',
                'description' => 'Quinto capítulo de la serie Romanos',
                'audio_file' => '1641336828.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:53:48'),
                'updated_at' => Carbon::parse('2022-01-04 17:53:48'),
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'title' => 'Romanos - El evangelio de verdad (primera parte)',
                'description' => 'Sexto capítulo de la serie Romanos',
                'audio_file' => '1641336872.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:54:32'),
                'updated_at' => Carbon::parse('2022-01-04 17:54:32'),
                'deleted_at' => null,
            ],
            [
                'id' => 7,
                'title' => 'Romanos - El evangelio de verdad (segunda parte)',
                'description' => 'Séptimo capítulo de la serie Romanos',
                'audio_file' => '1641336918.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:55:18'),
                'updated_at' => Carbon::parse('2022-01-04 17:55:18'),
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'title' => 'Romanos - El evangelio de verdad (tercera parte)',
                'description' => 'Octavo capítulo de la serie Romanos',
                'audio_file' => '1641336957.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:55:57'),
                'updated_at' => Carbon::parse('2022-01-04 17:55:57'),
                'deleted_at' => null,
            ],
            [
                'id' => 9,
                'title' => 'Romanos - La genealogía de Jesús (primera parte)',
                'description' => 'Noveno capítulo de la serie Romanos',
                'audio_file' => '1641337021.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:57:01'),
                'updated_at' => Carbon::parse('2022-01-04 17:57:01'),
                'deleted_at' => null,
            ],
            [
                'id' => 10,
                'title' => 'Romanos - Le genealogía de Jesús (segunda parte)',
                'description' => 'Décimo capítulo de la serie Romanos',
                'audio_file' => '1641337065.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:57:46'),
                'updated_at' => Carbon::parse('2022-01-04 17:57:46'),
                'deleted_at' => null,
            ],
            [
                'id' => 11,
                'title' => 'Romanos - La maldición generacional',
                'description' => 'Undécimo capítulo de la serie Romanos',
                'audio_file' => '1641337107.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:58:28'),
                'updated_at' => Carbon::parse('2022-01-04 17:58:28'),
                'deleted_at' => null,
            ],
            [
                'id' => 12,
                'title' => 'Romanos - El bosquejo de la verdad de Jesús',
                'description' => 'Duodécimo capítulo de la serie Romanos',
                'audio_file' => '1641337167.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 17:59:27'),
                'updated_at' => Carbon::parse('2022-01-04 17:59:27'),
                'deleted_at' => null,
            ],
            [
                'id' => 13,
                'title' => 'Romanos - Sujeto del evangelio (primera parte)',
                'description' => 'Décimo tercer capítulo de la serie Romanos',
                'audio_file' => '1641337204.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:00:05'),
                'updated_at' => Carbon::parse('2022-01-04 18:00:31'),
                'deleted_at' => null,
            ],
            [
                'id' => 14,
                'title' => 'Romanos - Sujeto del evangelio (segunda parte)',
                'description' => 'Décimo cuarto capítulo de la serie Romanos',
                'audio_file' => '1641337275.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:01:15'),
                'updated_at' => Carbon::parse('2022-01-04 18:01:15'),
                'deleted_at' => null,
            ],
            [
                'id' => 15,
                'title' => 'Romanos - Banalización de la escritura',
                'description' => 'Décimo quinto capítulo de la serie Romanos',
                'audio_file' => '1641337311.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:01:52'),
                'updated_at' => Carbon::parse('2022-01-04 18:01:52'),
                'deleted_at' => null,
            ],
            [
                'id' => 16,
                'title' => 'Romanos - La oración bíblica de Nehemías',
                'description' => 'Décimo sexto capítulo de la serie Romanos',
                'audio_file' => '1641337359.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:02:40'),
                'updated_at' => Carbon::parse('2022-01-04 18:02:40'),
                'deleted_at' => null,
            ],
            [
                'id' => 17,
                'title' => 'Romanos - Un destello de su gloria',
                'description' => 'Décimo séptimo capítulo de la serie Romanos',
                'audio_file' => '1641337403.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:03:23'),
                'updated_at' => Carbon::parse('2022-01-04 18:03:23'),
                'deleted_at' => null,
            ],

            [
                'id' => 18,
                'title' => 'Romanos - Fracasar',
                'description' => 'Décimo octavo capítulo de la serie Romanos',
                'audio_file' => '1641337442.mp3',
                'category_id' => 1,
                'created_at' => Carbon::parse('2022-01-04 18:04:03'),
                'updated_at' => Carbon::parse('2022-01-04 18:04:03'),
                'deleted_at' => null,
            ],
        ]);
    }
}
