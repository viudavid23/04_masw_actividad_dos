<?php

namespace Database\Seeders;

use App\Models\ActorSerie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActorSerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ActorSerie::factory(15)->create();
        ActorSerie::unguard();

        ActorSerie::factory()->create([
            'actor_id' => 1,
            'serie_id' => 1,
            'created_at' => '2024-09-05T05:14:32.000000Z',
            'updated_at' => '2024-09-05T05:14:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 2,
            'serie_id' => 1,
            'created_at' => '2024-09-06T06:15:32.000000Z',
            'updated_at' => '2024-09-06T06:15:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 2,
            'serie_id' => 3,
            'created_at' => '2024-09-07T07:16:32.000000Z',
            'updated_at' => '2024-09-08T08:17:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 2,
            'serie_id' => 4,
            'created_at' => '2024-09-09T09:18:32.000000Z',
            'updated_at' => '2024-09-10T10:19:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 3,
            'serie_id' => 5,
            'created_at' => '2024-09-11T11:20:32.000000Z',
            'updated_at' => '2024-09-11T11:20:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 4,
            'serie_id' => 2,
            'created_at' => '2024-09-12T12:21:32.000000Z',
            'updated_at' => '2024-09-13T13:22:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 4,
            'serie_id' => 5,
            'created_at' => '2024-09-14T14:23:32.000000Z',
            'updated_at' => '2024-09-15T15:24:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 5,
            'serie_id' => 4,
            'created_at' => '2024-09-16T16:25:32.000000Z',
            'updated_at' => '2024-09-17T17:26:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 5,
            'serie_id' => 5,
            'created_at' => '2024-09-18T18:27:32.000000Z',
            'updated_at' => '2024-09-19T19:28:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 6,
            'serie_id' => 2,
            'created_at' => '2024-09-20T20:29:32.000000Z',
            'updated_at' => '2024-09-20T20:29:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 6,
            'serie_id' => 4,
            'created_at' => '2024-09-21T21:30:32.000000Z',
            'updated_at' => '2024-09-22T22:31:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 8,
            'serie_id' => 3,
            'created_at' => '2024-09-23T23:32:32.000000Z',
            'updated_at' => '2024-09-23T23:32:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 8,
            'serie_id' => 5,
            'created_at' => '2024-09-24T00:33:32.000000Z',
            'updated_at' => '2024-09-25T01:34:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 9,
            'serie_id' => 4,
            'created_at' => '2024-09-26T02:35:32.000000Z',
            'updated_at' => '2024-09-27T03:36:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 9,
            'serie_id' => 5,
            'created_at' => '2024-09-28T04:37:32.000000Z',
            'updated_at' => '2024-09-29T05:38:32.000000Z',
        ]);
        
        ActorSerie::factory()->create([
            'actor_id' => 10,
            'serie_id' => 5,
            'created_at' => '2024-09-30T06:39:32.000000Z',
            'updated_at' => '2024-09-30T06:39:32.000000Z',
        ]);
        
        ActorSerie::reguard();
    }
}
