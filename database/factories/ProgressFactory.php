<?php

namespace Database\Factories;

use App\Models\Enrolement;
use App\Models\Progress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Progress>
 */
class ProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'enrolement_id' => Enrolement::query()->inRandomOrder()->value('id'),
        ];
    }
}
