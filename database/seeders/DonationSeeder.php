<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('donations')->insert([
            'reference' => 00000000,
            'name' => 'Datos de prueba',
            'email' => 'pruebas@pruebas.test',
            'phone'=> '6010000000',
            'address' => 'Avenida Calle 0 # 0 - 0',
            'amount' => 12345678,
            'message' => 'Este es un dato de prueba',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
