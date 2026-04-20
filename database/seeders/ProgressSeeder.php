<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Progress;
use Illuminate\Database\Seeder;

class ProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::with('lessons')->get()->each(function ($course) {
            $enrolement = $course->enrolements()->inRandomOrder()->value('id');
            $randumNumber = rand(3, 6);
            $course->lessons()->limit($randumNumber)->each(function ($lesson) use ($enrolement) {
                Progress::factory()->create([
                    'lesson_id' => $lesson->id,
                    'enrolement_id' => $enrolement,
                ]);
            });
        });
    }
}
