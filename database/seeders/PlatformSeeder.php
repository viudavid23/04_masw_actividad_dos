<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Platform;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Platform::factory(10)->create();
        Platform::unguard();

        Platform::factory()->create([
            'id' => 1,
            'name' => 'NETFLIX',
            'description' => 'Empresa de entretenimiento y una plataforma de streaming estadounidense',
            'release_date' => '2007-01-15',
            'logo' => null
        ]);

        Platform::factory()->create([
            'id' => 2,
            'name' => 'HBO',
            'description' => 'Home Box Office es una cadena de televisión por suscripción estadounidense, propiedad de Warner Bros',
            'release_date' => '2015-04-07',
            'logo' => null
        ]);

        Platform::factory()->create([
            'id' => 3,
            'name' => 'DISNEY+',
            'description' => 'Servicio de streaming propiedad de The Walt Disney Company mediante su división Disney Media and Ent',
            'release_date' => '2019-11-12',
            'logo' => null
        ]);

        Platform::factory()->create([
            'id' => 4,
            'name' => 'AMAZON PRIME VIDEO',
            'description' => 'Es un servicio de streaming OTT de películas y series creado y gestionado por Amazon',
            'release_date' => '2016-12-14',
            'logo' => null
        ]);

        Platform::reguard();
    }
}