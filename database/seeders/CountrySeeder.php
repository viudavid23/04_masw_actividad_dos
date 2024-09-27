<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Country::factory(10)->create();
        Country::unguard();

        Country::factory()->create([
            'id' => 1,
            'name' => 'ESPAÑA',
            'demonym' => 'ESPAÑOL/LA'
        ]);

        Country::factory()->create([
            'id' => 2,
            'name' => 'COLOMBIA',
            'demonym' => 'COLOMBIANO/A'
        ]);

        Country::factory()->create([
            'id' => 3,
            'name' => 'BRASIL',
            'demonym' => 'BRASILEÑO/A'
        ]);

        Country::factory()->create([
            'id' => 4,
            'name' => 'NORUEGA',
            'demonym' => 'NORUEGO/A'
        ]);

        Country::factory()->create([
            'id' => 5,
            'name' => 'ITALIA',
            'demonym' => 'ITALIANO/A'
        ]);

        Country::factory()->create([
            'id' => 6,
            'name' => 'JAPÓN',
            'demonym' => 'JAPONÉS/A'
        ]);

        Country::factory()->create([
            'id' => 7,
            'name' => 'ESTADOS UNIDOS',
            'demonym' => 'ESTADOUNIDENSE'
        ]);

        Country::factory()->create([
            'id' => 8,
            'name' => 'MEXICO',
            'demonym' => 'MEXICANO/A'
        ]);

        Country::factory()->create([
            'id' => 9,
            'name' => 'CANADÁ',
            'demonym' => 'CANADIENSE'
        ]);

        Country::factory()->create([
            'id' => 10,
            'name' => 'PORTUGAL',
            'demonym' => 'PORTUGUÉS'
        ]);

        Country::reguard();
    }
}
