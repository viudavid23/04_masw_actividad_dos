<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Director>
 */
class DirectorFactory extends Factory
{
    // Variable estática para mantener un contador
    protected static $sequence = 15;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'beginning_career' => fake()->dateTimeBetween('-1 year', 'now'),
            'active_years' => self::$sequence++,
            'biography' => fake()->paragraph(),
            'awards' => fake()->paragraph(),
            'people_id' => self::$sequence++,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
