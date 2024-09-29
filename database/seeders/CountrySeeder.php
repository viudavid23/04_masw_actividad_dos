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
            'demonym' => 'ESPAÑOL/LA',
            'created_at' => '2024-09-01T05:14:32.000000Z',
            'updated_at' => '2024-09-01T05:14:32.000000Z',
        ]);
        
        Country::factory()->create([
            'id' => 2,
            'name' => 'COLOMBIA',
            'demonym' => 'COLOMBIANO/A',
            'created_at' => '2024-09-02T06:15:32.000000Z',
            'updated_at' => '2024-09-02T06:15:32.000000Z',
        ]);
        
        Country::factory()->create([
            'id' => 3,
            'name' => 'BRASIL',
            'demonym' => 'BRASILEÑO/A',
            'created_at' => '2024-09-03T07:16:32.000000Z',
            'updated_at' => '2024-09-04T08:17:32.000000Z', 
        ]);
        
        Country::factory()->create([
            'id' => 4,
            'name' => 'NORUEGA',
            'demonym' => 'NORUEGO/A',
            'created_at' => '2024-09-05T09:18:32.000000Z',
            'updated_at' => '2024-09-05T09:18:32.000000Z',
        ]);
        
        Country::factory()->create([
            'id' => 5,
            'name' => 'ITALIA',
            'demonym' => 'ITALIANO/A',
            'created_at' => '2024-09-06T10:19:32.000000Z',
            'updated_at' => '2024-09-06T10:19:32.000000Z',
        ]);
        
        Country::factory()->create([
            'id' => 6,
            'name' => 'JAPÓN',
            'demonym' => 'JAPONÉS/A',
            'created_at' => '2024-09-07T11:20:32.000000Z',
            'updated_at' => '2024-09-08T12:21:32.000000Z', 
        ]);
        
        Country::factory()->create([
            'id' => 7,
            'name' => 'ESTADOS UNIDOS',
            'demonym' => 'ESTADOUNIDENSE',
            'created_at' => '2024-09-09T13:22:32.000000Z',
            'updated_at' => '2024-09-10T14:23:32.000000Z', 
        ]);
        
        Country::factory()->create([
            'id' => 8,
            'name' => 'MEXICO',
            'demonym' => 'MEXICANO/A',
            'created_at' => '2024-09-11T15:24:32.000000Z',
            'updated_at' => '2024-09-12T16:25:32.000000Z', 
        ]);
        
        Country::factory()->create([
            'id' => 9,
            'name' => 'CANADÁ',
            'demonym' => 'CANADIENSE',
            'created_at' => '2024-09-13T17:26:32.000000Z',
            'updated_at' => '2024-09-13T17:26:32.000000Z',
        ]);
        
        Country::factory()->create([
            'id' => 10,
            'name' => 'PORTUGAL',
            'demonym' => 'PORTUGUÉS',
            'created_at' => '2024-09-14T18:27:32.000000Z',
            'updated_at' => '2024-09-15T19:28:32.000000Z', 
        ]);        

        Country::reguard();
    }
}
