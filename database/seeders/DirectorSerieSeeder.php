<?php

namespace Database\Seeders;

use App\Models\DirectorSerie;
use Illuminate\Database\Seeder;

class DirectorSerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Director::factory(10)->create();
        DirectorSerie::unguard();

        DirectorSerie::factory()->create([
            'director_id' => 1,
            'serie_id' => 1,
            'created_at' => '2024-06-16T09:00:00.000000Z',
            'updated_at' => '2024-06-17T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 2,
            'serie_id' => 2,
            'created_at' => '2024-06-18T09:00:00.000000Z',
            'updated_at' => '2024-06-19T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 3,
            'serie_id' => 4,
            'created_at' => '2024-06-20T09:00:00.000000Z',
            'updated_at' => '2024-06-21T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 4,
            'serie_id' => 2,
            'created_at' => '2024-06-22T09:00:00.000000Z',
            'updated_at' => '2024-06-23T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 4,
            'serie_id' => 3,
            'created_at' => '2024-06-24T09:00:00.000000Z',
            'updated_at' => '2024-06-25T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 5,
            'serie_id' => 1,
            'created_at' => '2024-06-26T09:00:00.000000Z',
            'updated_at' => '2024-06-27T09:00:00.000000Z',
        ]);
        
        DirectorSerie::factory()->create([
            'director_id' => 5,
            'serie_id' => 5,
            'created_at' => '2024-06-28T09:00:00.000000Z',
            'updated_at' => '2024-06-29T09:00:00.000000Z',
        ]);        
       
        DirectorSerie::reguard();
    }
}
