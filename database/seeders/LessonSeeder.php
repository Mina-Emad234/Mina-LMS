<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function ($course) {
            $lessonCount = rand(5, 12); // مطلوب من 5 لـ 12 درس لكل كورس
            for ($i = 1; $i <= $lessonCount; $i++) {
                Lesson::factory()->create([
                    'course_id' => $course->id,
                    'course_section_id' => $course->sections()->inRandomOrder()->first()->id,
                    'order' => $i,
                ]);
            }
        });
    }
}
