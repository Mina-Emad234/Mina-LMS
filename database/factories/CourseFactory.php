<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'target_audience' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review_count' => $this->faker->numberBetween(0, 1000),
            'is_featured' => $this->faker->boolean(),
            'category_id' => Category::factory(),
            'level_id' => Level::factory(),
            'instructor_id' => Instructor::factory(),
        ];
    }
}
