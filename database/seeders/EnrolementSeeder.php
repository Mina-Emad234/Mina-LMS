<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrolement;
use Illuminate\Database\Seeder;

class EnrolementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function ($course) {
            for ($i = 1; $i <= 10; $i++) {
                Enrolement::factory()->create([
                    'course_id' => $course->id,
                ]);
            }
        });
    }
}
