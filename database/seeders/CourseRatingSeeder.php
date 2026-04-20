<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRating;
use Illuminate\Database\Seeder;

class CourseRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function ($course) {
            $ratingCount = rand(10, 20);
            for ($i = 1; $i <= $ratingCount; $i++) {
                CourseRating::factory()->create([
                    'course_id' => $course->id,
                ]);
            }
        });
    }
}
