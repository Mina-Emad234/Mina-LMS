<?php

namespace Database\Factories;

use App\Models\Enrolement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrolement>
 */
class EnrolementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enroled_at' => $this->faker->date(),
            'is_completed' => false,
            'completed_at' => null,
            'user_id' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
