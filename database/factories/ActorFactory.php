<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Actor>
 */
class ActorFactory extends Factory
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
            'stage_name' => fake()->text(),
            'biography' => fake()->paragraph(),
            'awards' => fake()->paragraph(),
            'height' => fake()->randomFloat(2, 10, 250.00),
            'people_id' => self::$sequence++,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
