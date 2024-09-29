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
            'serie_id' => 1
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 2
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 4
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 1,
            'serie_id' => 5
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 2,
            'serie_id' => 5
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 3,
            'serie_id' => 3
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 3,
            'serie_id' => 5
        ]);
        
        PlatformSerie::factory()->create([
            'platform_id' => 4,
            'serie_id' => 4
        ]);

        PlatformSerie::reguard();
    }
}
