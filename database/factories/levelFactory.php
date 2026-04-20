<?php

namespace Database\Factories;

use App\Models\level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<level>
 */
class levelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'color' => $this->faker->hexColor,
        ];
    }
}
