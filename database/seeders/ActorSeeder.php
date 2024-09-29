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
         // Actor::factory(10)->create();
         Actor::unguard();

         Actor::factory()->create([
            'id' => 1,
            'stage_name' => 'THE ROCK',
            'biography' => 'Famoso actor de acción.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.96,
            'people_id' => 1,
            'created_at' => '2024-05-06T09:00:00.000000Z',
            'updated_at' => '2024-05-07T09:00:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 2,
            'stage_name' => 'SCARLETT JOHANSSON',
            'biography' => 'Actriz ganadora de premios.',
            'awards' => 'BAFTA, Tony Award.',
            'height' => 1.60,
            'people_id' => 2,
            'created_at' => '2024-05-07T09:15:00.000000Z',
            'updated_at' => '2024-05-08T09:15:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 3,
            'stage_name' => 'BRAD PITT',
            'biography' => 'Actor destacado en Hollywood.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.80,
            'people_id' => 3,
            'created_at' => '2024-05-08T09:30:00.000000Z',
            'updated_at' => '2024-05-09T09:30:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 4,
            'stage_name' => 'ANGELINA JOLIE',
            'biography' => 'Humanitaria y actriz reconocida.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.69,
            'people_id' => 4,
            'created_at' => '2024-05-09T09:45:00.000000Z',
            'updated_at' => '2024-05-10T09:45:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 5,
            'stage_name' => 'WILL SMITH',
            'biography' => 'Actor y productor popular.',
            'awards' => 'Oscar, Grammy.',
            'height' => 1.88,
            'people_id' => 5,
            'created_at' => '2024-05-10T10:00:00.000000Z',
            'updated_at' => '2024-05-11T10:00:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 6,
            'stage_name' => 'NATALIE PORTMAN',
            'biography' => 'Estrella de muchas películas.',
            'awards' => 'Oscar, BAFTA.',
            'height' => 1.60,
            'people_id' => 6,
            'created_at' => '2024-05-11T10:15:00.000000Z',
            'updated_at' => '2024-05-12T10:15:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 7,
            'stage_name' => 'LEONARDO DICAPRIO',
            'biography' => 'Activista ambiental.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.83,
            'people_id' => 7,
            'created_at' => '2024-05-12T10:30:00.000000Z',
            'updated_at' => '2024-05-13T10:30:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 8,
            'stage_name' => 'EMMA WATSON',
            'biography' => 'Famosa por las películas de Harry Potter.',
            'awards' => 'BAFTA, Teen Choice.',
            'height' => 1.65,
            'people_id' => 8,
            'created_at' => '2024-05-13T10:45:00.000000Z',
            'updated_at' => '2024-05-14T10:45:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 9,
            'stage_name' => 'ROBERT DOWNEY JR',
            'biography' => 'Iron Man en las películas de Marvel.',
            'awards' => 'Oscar, Globo de Oro.',
            'height' => 1.74,
            'people_id' => 9,
            'created_at' => '2024-05-14T11:00:00.000000Z',
            'updated_at' => '2024-05-15T11:00:00.000000Z', 
        ]);
        
        Actor::factory()->create([
            'id' => 10,
            'stage_name' => 'CHRIS HEMSWORTH',
            'biography' => 'Conocido como Thor.',
            'awards' => 'Premio People’s Choice.',
            'height' => 1.91,
            'people_id' => 10,
            'created_at' => '2024-05-15T11:15:00.000000Z',
            'updated_at' => '2024-05-16T11:15:00.000000Z', 
        ]);        
        
         Actor::reguard();
    }
}
