<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Database\Seeder;

class CourseSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function ($course) {
            $sectionCount = rand(3, 6);
            for ($i = 1; $i <= $sectionCount; $i++) {
                CourseSection::factory()->create([
                    'course_id' => $course->id,
                    'order' => $i,
                ]);
            }
        });
    }
}
