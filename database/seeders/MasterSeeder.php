<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DatabaseSeeder::class,
            CountrySeeder::class,
            PersonSeeder::class,
            ActorSeeder::class,
            DirectorSeeder::class,
            LanguageSeeder::class,
            PlatformSeeder::class,
            SerieSeeder::class,
            PlatformSerieSeeder::class,
            ActorSerieSeeder::class,

        ]);
    }
}
