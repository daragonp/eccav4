<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\NewsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            WeekSeeder::class,
            RolesAndPermissionsSeeder::class,
            NewsTableSeeder::class,
            PodCastTableSeeder::class,
                ]);
    }
}
