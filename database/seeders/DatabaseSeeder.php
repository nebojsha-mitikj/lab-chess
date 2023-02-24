<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Goals
            DailyGoalSeeder::class,
            MedalSeeder::class,

            // Themes
            BoardThemeSeeder::class,
            PieceThemeSeeder::class,

            // Trainers
            TrainerSeeder::class,
            TrainerVariantSeeder::class,
            TrainerPositionSeeder::class,

            // Courses
            CourseSeeder::class,
            LectureSeeder::class,

            // User Level
            UserLevelSeeder::class
        ]);
    }
}
