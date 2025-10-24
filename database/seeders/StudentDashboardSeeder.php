<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class StudentDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@studybuddy.test'],
            [
                'name' => 'Dr. Sarah Johnson',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]
        );

        // Update role if already exists
        $teacher->update(['role' => 'teacher']);

        // Create a test student
        $student = User::firstOrCreate(
            ['email' => 'student@studybuddy.test'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // Update role if already exists
        $student->update(['role' => 'student']);

        // Create sample tasks
        $tasks = [
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Complete Chapter 5 Reading',
                'description' => 'Read and summarize the key concepts from Chapter 5 of the textbook.',
                'deadline' => now()->addDays(3),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Math Problem Set #7',
                'description' => 'Solve problems 1-20 from the problem set. Show all work.',
                'deadline' => now()->addDays(5),
                'status' => 'in_progress',
                'priority' => 'medium',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Lab Report Submission',
                'description' => 'Submit the chemistry lab report with all required sections.',
                'deadline' => now()->subDays(1),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Essay Draft',
                'description' => 'Submit first draft of the English essay on climate change.',
                'deadline' => now()->addWeek(),
                'status' => 'pending',
                'priority' => 'medium',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Biology Quiz Preparation',
                'description' => 'Review chapters 8-10 for the upcoming quiz.',
                'deadline' => now()->addDays(2),
                'status' => 'pending',
                'priority' => 'high',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Group Project Part 1',
                'description' => 'Complete research phase of group project.',
                'deadline' => now()->subDays(2),
                'status' => 'completed',
                'priority' => 'low',
                'completed_at' => now()->subDays(3),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::firstOrCreate(
                [
                    'student_id' => $taskData['student_id'],
                    'title' => $taskData['title']
                ],
                $taskData
            );
        }

        // Create sample meetings
        $meetings = [
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Mid-term Progress Review',
                'description' => 'Discuss academic progress and upcoming assignments.',
                'scheduled_at' => now()->addDays(2)->setTime(14, 0),
                'duration_minutes' => 30,
                'location' => 'Room 205',
                'status' => 'scheduled',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Math Tutoring Session',
                'description' => 'Extra help with calculus concepts.',
                'scheduled_at' => now()->addDays(4)->setTime(15, 30),
                'duration_minutes' => 45,
                'meeting_link' => 'https://meet.google.com/abc-defg-hij',
                'status' => 'scheduled',
            ],
            [
                'student_id' => $student->id,
                'teacher_id' => $teacher->id,
                'title' => 'Project Consultation',
                'description' => 'Review project proposal and get feedback.',
                'scheduled_at' => now()->addWeek()->setTime(10, 0),
                'duration_minutes' => 30,
                'location' => 'Office 312',
                'status' => 'scheduled',
            ],
        ];

        foreach ($meetings as $meetingData) {
            Meeting::firstOrCreate(
                [
                    'student_id' => $meetingData['student_id'],
                    'title' => $meetingData['title']
                ],
                $meetingData
            );
        }

        // Create sample notifications
        $notifications = [
            [
                'user_id' => $student->id,
                'sender_id' => $teacher->id,
                'title' => 'New Assignment Posted',
                'message' => 'A new assignment "Complete Chapter 5 Reading" has been assigned to you. Due date: ' . now()->addDays(3)->format('M d, Y'),
                'type' => 'task',
                'is_read' => false,
            ],
            [
                'user_id' => $student->id,
                'sender_id' => $teacher->id,
                'title' => 'Upcoming Meeting Reminder',
                'message' => 'You have a meeting scheduled for tomorrow at 2:00 PM. Please be on time.',
                'type' => 'meeting',
                'is_read' => false,
            ],
            [
                'user_id' => $student->id,
                'sender_id' => $teacher->id,
                'title' => 'Great work on your project!',
                'message' => 'Your Group Project Part 1 submission was excellent. Keep up the good work!',
                'type' => 'feedback',
                'is_read' => false,
            ],
            [
                'user_id' => $student->id,
                'sender_id' => null,
                'title' => 'School Holiday Announcement',
                'message' => 'The school will be closed next Friday for a staff development day.',
                'type' => 'announcement',
                'is_read' => true,
            ],
            [
                'user_id' => $student->id,
                'sender_id' => $teacher->id,
                'title' => 'Lab Report Overdue',
                'message' => 'Your Lab Report Submission is now overdue. Please submit as soon as possible.',
                'type' => 'task',
                'is_read' => false,
            ],
        ];

        foreach ($notifications as $notificationData) {
            Notification::firstOrCreate(
                [
                    'user_id' => $notificationData['user_id'],
                    'title' => $notificationData['title']
                ],
                $notificationData
            );
        }

        $this->command->info('✅ Student dashboard sample data seeded successfully!');
        $this->command->info('📧 Test Login:');
        $this->command->info('   Student: student@studybuddy.test / password');
        $this->command->info('   Teacher: teacher@studybuddy.test / password');
    }
}
