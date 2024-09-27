<?php

namespace Database\Seeders;

use App\Models\Director;
use Illuminate\Database\Seeder;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Director::factory(15)->create();
        Director::unguard();

        Director::factory()->create([
            'id' => 1,
            'beginning_career' => '1995-05-20',
            'active_years' => 25,
            'biography' => 'Director conocido por su estilo único.',
            'awards' => 'Oscar, Globo de Oro.',
            'person_id' => 11
        ]);
        
        Director::factory()->create([
            'id' => 2,
            'beginning_career' => '2000-08-15',
            'active_years' => 20,
            'biography' => 'Ha dirigido varias películas aclamadas.',
            'awards' => 'BAFTA, Premio del Sindicato.',
            'person_id' => 12
        ]);
        
        Director::factory()->create([
            'id' => 3,
            'beginning_career' => '1985-11-10',
            'active_years' => 35,
            'biography' => 'Reconocido por su trabajo en el cine independiente.',
            'awards' => 'Oscar, Palma de Oro.',
            'person_id' => 13
        ]);
        
        Director::factory()->create([
            'id' => 4,
            'beginning_career' => '2010-03-05',
            'active_years' => 14,
            'biography' => 'Joven director en ascenso.',
            'awards' => 'Premio de la Crítica.',
            'person_id' => 4
        ]);
        
        Director::factory()->create([
            'id' => 5,
            'beginning_career' => '1992-09-12',
            'active_years' => 28,
            'biography' => 'Ha trabajado con grandes actores.',
            'awards' => 'Globo de Oro, Emmy.',
            'person_id' => 5
        ]);        

        Director::reguard();
    }
}
