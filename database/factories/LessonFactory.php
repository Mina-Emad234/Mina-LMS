<?php

namespace Database\Factories;

use App\Enums\VideoTypeEnum;
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
            'video_id' => $this->faker->regexify('[A-Za-z0-9]{11}'),
            'video_type' => $this->faker->randomElement(VideoTypeEnum::values()),
            'duration' => rand(40, 50),
            'is_published' => $this->faker->boolean(),
        ];
    }
}
