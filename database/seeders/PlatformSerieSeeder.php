<?php

namespace Database\Seeders;

use App\Models\PlatformSerie;
use Illuminate\Database\Seeder;

class PlatformSerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PlatformSerie::factory(8)->create();
        PlatformSerie::unguard();

        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 1,
            'created_at' => '2024-04-27T10:00:00.000000Z',
            'updated_at' => '2024-04-28T10:00:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 2,
            'created_at' => '2024-04-28T10:15:00.000000Z',
            'updated_at' => '2024-04-29T10:15:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 4,
            'created_at' => '2024-04-29T10:30:00.000000Z',
            'updated_at' => '2024-04-30T10:30:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 5,
            'created_at' => '2024-04-30T10:45:00.000000Z',
            'updated_at' => '2024-05-01T10:45:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 2,
            'serie_id' => 5,
            'created_at' => '2024-05-01T11:00:00.000000Z',
            'updated_at' => '2024-05-02T11:00:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 3,
            'serie_id' => 3,
            'created_at' => '2024-05-02T11:15:00.000000Z',
            'updated_at' => '2024-05-03T11:15:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 3,
            'serie_id' => 5,
            'created_at' => '2024-05-03T11:30:00.000000Z',
            'updated_at' => '2024-05-04T11:30:00.000000Z', 
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 4,
            'serie_id' => 4,
            'created_at' => '2024-05-04T11:45:00.000000Z',
            'updated_at' => '2024-05-05T11:45:00.000000Z', 
        ]);
        
        PlatformSerie::reguard();
    }
}
