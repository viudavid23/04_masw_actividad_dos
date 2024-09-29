<?php

namespace Database\Seeders;

use App\Models\LanguageSerie;
use Illuminate\Database\Seeder;

class LanguageSerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // LanguageSerie::factory(10)->create();
        
        LanguageSerie::unguard();

        LanguageSerie::factory()->create([
            'language_id' => 9,
            'serie_id' => 1,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-07-26T08:00:00.000000Z',
            'updated_at' => '2024-07-27T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 8,
            'serie_id' => 2,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-07-28T08:00:00.000000Z',
            'updated_at' => '2024-07-29T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 7,
            'serie_id' => 4,
            'audio' => 1,
            'subtitle' => 0,
            'created_at' => '2024-07-30T08:00:00.000000Z',
            'updated_at' => '2024-07-31T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 2,
            'serie_id' => 3,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-01T08:00:00.000000Z',
            'updated_at' => '2024-08-02T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 6,
            'serie_id' => 4,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-08-03T08:00:00.000000Z',
            'updated_at' => '2024-08-04T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 2,
            'serie_id' => 5,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-05T08:00:00.000000Z',
            'updated_at' => '2024-08-06T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 3,
            'serie_id' => 4,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-07T08:00:00.000000Z',
            'updated_at' => '2024-08-08T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 3,
            'serie_id' => 2,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-09T08:00:00.000000Z',
            'updated_at' => '2024-08-10T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 3,
            'serie_id' => 5,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-08-11T08:00:00.000000Z',
            'updated_at' => '2024-08-12T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 4,
            'serie_id' => 1,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-08-13T08:00:00.000000Z',
            'updated_at' => '2024-08-14T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 4,
            'serie_id' => 2,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-15T08:00:00.000000Z',
            'updated_at' => '2024-08-16T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 4,
            'serie_id' => 3,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-17T08:00:00.000000Z',
            'updated_at' => '2024-08-18T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 5,
            'serie_id' => 2,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-19T08:00:00.000000Z',
            'updated_at' => '2024-08-20T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 5,
            'serie_id' => 5,
            'audio' => 0,
            'subtitle' => 1,
            'created_at' => '2024-08-21T08:00:00.000000Z',
            'updated_at' => '2024-08-22T12:00:00.000000Z',
        ]);
        
        LanguageSerie::factory()->create([
            'language_id' => 5,
            'serie_id' => 4,
            'audio' => 1,
            'subtitle' => 1,
            'created_at' => '2024-08-23T08:00:00.000000Z',
            'updated_at' => '2024-08-24T12:00:00.000000Z',
        ]);        
       
        LanguageSerie::reguard();
    }
}
