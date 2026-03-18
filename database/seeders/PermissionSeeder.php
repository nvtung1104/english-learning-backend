<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'Quản lý danh mục',
                'slug' => 'manage_categories',
            ],
            [
                'name' => 'Quản lý cấp độ',
                'slug' => 'manage_levels',
            ],
            [
                'name' => 'Quản lý loại bài học',
                'slug' => 'manage_lesson_types',
            ],
            [
                'name' => 'Quản lý khóa học',
                'slug' => 'manage_courses',
            ],
            [
                'name' => 'Quản lý section',
                'slug' => 'manage_sections',
            ],
            [
                'name' => 'Quản lý bài học',
                'slug' => 'manage_lessons',
            ],
            [
                'name' => 'Quản lý quiz',
                'slug' => 'manage_quizzes',
            ],
            [
                'name' => 'Quản lý thông báo',
                'slug' => 'manage_notifications',
            ],
            [
                'name' => 'Đăng ký khóa học',
                'slug' => 'enroll_courses',
            ],
            [
                'name' => 'Học bài',
                'slug' => 'learn_lessons',
            ],
            [
                'name' => 'Làm quiz',
                'slug' => 'submit_quizzes',
            ],
            [
                'name' => 'Viết đánh giá',
                'slug' => 'write_reviews',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}