<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Beginner', 'color' => '#10b981'], // green
            ['name' => 'Intermediate', 'color' => '#f59e0b'], // yellow
            ['name' => 'Advanced', 'color' => '#f97316'], // orange
            ['name' => 'Expert', 'color' => '#ef4444'], // red
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}
