<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Seed roles first
        $this->call(RoleSeeder::class);

        // User::factory(10)->create();

        // Create a test admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@studybuddy.com',
        ]);
        $admin->assignRole('admin');

        // Create a test teacher user
        $teacher = User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@studybuddy.com',
        ]);
        $teacher->assignRole('teacher');

        // Create a test student user
        $student = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@studybuddy.com',
        ]);
        $student->assignRole('student');
    }
}
