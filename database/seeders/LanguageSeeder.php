<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Platform::factory(10)->create();
        Language::unguard();

        Language::factory()->create([
            'id' => 1,
            'name' => 'NO APLICA',
            'iso_code' => 'ISO',
            'created_at' => '2024-09-01T05:14:32.000000Z',
            'updated_at' => '2024-09-01T05:14:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 2,
            'name' => 'ESPAÑOL',
            'iso_code' => 'ESP',
            'created_at' => '2024-09-02T06:15:32.000000Z',
            'updated_at' => '2024-09-02T06:15:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 3,
            'name' => 'ESPAÑOL',
            'iso_code' => 'CO',
            'created_at' => '2024-09-03T07:16:32.000000Z',
            'updated_at' => '2024-09-04T08:17:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 4,
            'name' => 'PORTUGUÉS',
            'iso_code' => '076',
            'created_at' => '2024-09-05T09:18:32.000000Z',
            'updated_at' => '2024-09-05T09:18:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 5,
            'name' => 'NORUEGO',
            'iso_code' => 'NO',
            'created_at' => '2024-09-06T10:19:32.000000Z',
            'updated_at' => '2024-09-06T10:19:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 6,
            'name' => 'ITALIANO',
            'iso_code' => 'IT',
            'created_at' => '2024-09-07T11:20:32.000000Z',
            'updated_at' => '2024-09-07T11:20:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 7,
            'name' => 'JAPONÉS',
            'iso_code' => 'JPN',
            'created_at' => '2024-09-08T12:21:32.000000Z',
            'updated_at' => '2024-09-09T13:22:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 8,
            'name' => 'INGLÉS',
            'iso_code' => 'USA',
            'created_at' => '2024-09-10T14:23:32.000000Z',
            'updated_at' => '2024-09-10T14:23:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 9,
            'name' => 'ESPAÑOL',
            'iso_code' => 'MX',
            'created_at' => '2024-09-11T15:24:32.000000Z',
            'updated_at' => '2024-09-11T15:24:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 10,
            'name' => 'INGLÉS',
            'iso_code' => '124',
            'created_at' => '2024-09-12T16:25:32.000000Z',
            'updated_at' => '2024-09-12T16:25:32.000000Z', 
        ]);
        
        Language::factory()->create([
            'id' => 11,
            'name' => 'PORTUGUÉS',
            'iso_code' => 'PRT',
            'created_at' => '2024-09-13T17:26:32.000000Z',
            'updated_at' => '2024-09-14T18:27:32.000000Z', 
        ]);        
       
        Language::reguard();
    }
}
