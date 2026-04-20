<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            LevelSeeder::class,
            InstructorSeeder::class,
            CourseSeeder::class,
            StudentSeeder::class,
            CourseSectionSeeder::class,
            LessonSeeder::class,
            EnrolementSeeder::class,
            CourseRatingSeeder::class,
            ProgressSeeder::class,
        ]);
    }
}
