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
            'iso_code' => 'ISO'
        ]);

        Language::factory()->create([
            'id' => 2,
            'name' => 'ESPAÑOL',
            'iso_code' => 'ESP'
        ]);

        Language::factory()->create([
            'id' => 3,
            'name' => 'ESPAÑOL',
            'iso_code' => 'CO'
        ]);

        Language::factory()->create([
            'id' => 4,
            'name' => 'PORTUGUÉS',
            'iso_code' => '076'
        ]);

        Language::factory()->create([
            'id' => 5,
            'name' => 'NORUEGO',
            'iso_code' => 'NO'
        ]);

        Language::factory()->create([
            'id' => 6,
            'name' => 'ITALIANO',
            'iso_code' => 'IT'
        ]);

        Language::factory()->create([
            'id' => 7,
            'name' => 'JAPONÉS',
            'iso_code' => 'JPN'
        ]);

        Language::factory()->create([
            'id' => 8,
            'name' => 'INGLÉS',
            'iso_code' => 'USA'
        ]);

        Language::factory()->create([
            'id' => 9,
            'name' => 'ESPAÑOL',
            'iso_code' => 'MX'
        ]);

        Language::factory()->create([
            'id' => 10,
            'name' => 'INGLÉS',
            'iso_code' => '124'
        ]);

        Language::factory()->create([
            'id' => 11,
            'name' => 'PORTUGUÉS',
            'iso_code' => 'PRT'
        ]);
       
        Language::reguard();
    }
}
