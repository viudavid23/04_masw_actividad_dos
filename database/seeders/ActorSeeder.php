<?php

namespace Database\Seeders;

use App\Models\Actor;
use Illuminate\Database\Seeder;

class ActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Person::factory(10)->create();
         Actor::unguard();

         Actor::factory()->create([
             'id' => 1,
             'stage_name' => 'THE ROCK',
             'biography' => 'Famoso actor de acción.',
             'awards' => 'Oscar, Globo de Oro.',
             'height' => 1.96,
             'people_id' => 1
         ]);

         Actor::factory()->create([
            'id' => 2,
            'stage_name' => 'SCARLETT JOHANSSON',
            'biography' => 'Actriz ganadora de premios.',
            'awards' => 'BAFTA, Tony Award.',
            'height' => 1.60,
            'people_id' => 2
        ]);
        
        Actor::factory()->create([
            'id' => 3,
            'stage_name' => 'BRAD PITT',
            'biography' => 'Actor destacado en Hollywood.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.80,
            'people_id' => 3
        ]);
        
        Actor::factory()->create([
            'id' => 4,
            'stage_name' => 'ANGELINA JOLIE',
            'biography' => 'Humanitaria y actriz reconocida.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.69,
            'people_id' => 4
        ]);
        
        Actor::factory()->create([
            'id' => 5,
            'stage_name' => 'WILL SMITH',
            'biography' => 'Actor y productor popular.',
            'awards' => 'Oscar, Grammy.',
            'height' => 1.88,
            'people_id' => 5
        ]);
        
        Actor::factory()->create([
            'id' => 6,
            'stage_name' => 'NATALIE PORTMAN',
            'biography' => 'Estrella de muchas películas.',
            'awards' => 'Oscar, BAFTA.',
            'height' => 1.60,
            'people_id' => 6
        ]);
        
        Actor::factory()->create([
            'id' => 7,
            'stage_name' => 'LEONARDO DICAPRIO',
            'biography' => 'Activista ambiental.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.83,
            'people_id' => 7
        ]);
        
        Actor::factory()->create([
            'id' => 8,
            'stage_name' => 'EMMA WATSON',
            'biography' => 'Famosa por las películas de Harry Potter.',
            'awards' => 'BAFTA, Teen Choice.',
            'height' => 1.65,
            'people_id' => 8
        ]);
        
        Actor::factory()->create([
            'id' => 9,
            'stage_name' => 'ROBERT DOWNEY JR',
            'biography' => 'Iron Man en las películas de Marvel.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.74,
            'people_id' => 9
        ]);
        
        Actor::factory()->create([
            'id' => 10,
            'stage_name' => 'CHRIS HEMSWORTH',
            'biography' => 'Conocido como Thor.',
            'awards' => 'Premio People’s Choice.',
            'height' => 1.91,
            'people_id' => 10
        ]);
        
         Actor::reguard();
    }
}
