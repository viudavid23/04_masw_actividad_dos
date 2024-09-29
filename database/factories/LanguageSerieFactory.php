<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LanguageSerie>
 */
class LanguageSerieFactory extends Factory
{

    // Variable estática para mantener un contador
    protected static $sequenceLanguageId = 10;

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
            'language_id' => self::$sequenceLanguageId++,
            'serie_id' => self::$sequenceSerieId++,
            'audio' => fake()->randomElement([0, 1]),
            'subtitle' => fake()->randomElement([0, 1]),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
