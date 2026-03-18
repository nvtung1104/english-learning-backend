<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,

            CourseCategorySeeder::class,
            CourseLevelSeeder::class,
            LessonTypeSeeder::class,

            CourseSeeder::class,
            CourseSectionSeeder::class,
            LessonSeeder::class,

            QuizSeeder::class,
            QuizQuestionSeeder::class,
            QuizAnswerSeeder::class,
        ]);
    }
}