<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DirectorSerie>
 */
class DirectorSerieFactory extends Factory
{
    // Variable estática para mantener un contador
    protected static $sequenceDirectorId = 10;

    // Variable estática para mantener un contador
    protected static $sequenceSerieId = 10;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'director_id' => self::$sequenceDirectorId++,
            'serie_id' => self::$sequenceSerieId++,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
