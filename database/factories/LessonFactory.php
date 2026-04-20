<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
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
            'learnings' => $this->faker->paragraph(),
            'video_url' => 'https://example.com/video.mp4',
            'duration' => rand(40, 50),
            'is_published' => $this->faker->boolean(),
        ];
    }
}
