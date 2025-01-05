<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert([
            'name' => 'Lumbrera a mi camino - Romanos',
            'description'=>'Contiene audios de la serie romanos, plan de Dios para las naciones y reconciliando los conflictos',
            'subcategory' => 'Romanos',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('categories')->insert([
            'name' => 'Lumbrera a mi camino - Plan de Dios',
            'description'=>'Contiene audios de la serie romanos, plan de Dios para las naciones y reconciliando los conflictos',
            'subcategory' => 'Plan de Dios para las naciones',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('categories')->insert([
            'name' => 'Lumbrera a mi camino - Reconciliando',
            'description'=>'Contiene audios de la serie romanos, plan de Dios para las naciones y reconciliando los conflictos',
            'subcategory' => 'Reconciliando los conflictos',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('categories')->insert([
            'name' => 'Viéndome con los ojos de Dios',
            'description'=>'¿Cómo le gustaría a Dios verme? La respuesta está en la biblia. ¡Escucha lo que dice el creador!',
            'subcategory' => Null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('categories')->insert([
            'name' => 'La herencia espiritual de las generaciones',
            'description'=>'El legado que cada uno ha dejado en su paso por este mundo material.',
            'subcategory' => Null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
