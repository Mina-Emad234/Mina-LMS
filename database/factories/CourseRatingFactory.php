<?php

namespace Database\Factories;

use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseRating>
 */
class CourseRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'user_id' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
