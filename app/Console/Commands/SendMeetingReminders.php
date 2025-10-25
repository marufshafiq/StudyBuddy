<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use App\Models\Notification;
use Carbon\Carbon;

class SendMeetingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meetings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send meeting reminders 30 minutes before scheduled time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get meetings that start in 30 minutes (with 1-minute window)
        $reminderTime = now()->addMinutes(30);
        
        $meetings = Meeting::where('status', 'scheduled')
            ->where('request_status', 'approved')
            ->whereBetween('scheduled_at', [
                $reminderTime->copy()->subMinute(),
                $reminderTime->copy()->addMinute()
            ])
            ->with(['student', 'teacher'])
            ->get();

        if ($meetings->isEmpty()) {
            $this->info('No meetings starting in 30 minutes.');
            return 0;
        }

        foreach ($meetings as $meeting) {
            // Send notification to student
            if ($meeting->student_id) {
                Notification::create([
                    'user_id' => $meeting->student_id,
                    'sender_id' => null,
                    'type' => 'meeting',
                    'title' => 'Meeting Reminder',
                    'message' => 'Your meeting "' . $meeting->title . '" with ' . ($meeting->teacher->name ?? 'teacher') . ' starts in 30 minutes at ' . $meeting->scheduled_at->format('h:i A') . '.',
                    'is_read' => false,
                ]);
            }

            // Send notification to teacher
            if ($meeting->teacher_id) {
                Notification::create([
                    'user_id' => $meeting->teacher_id,
                    'sender_id' => null,
                    'type' => 'meeting',
                    'title' => 'Meeting Reminder',
                    'message' => 'Your meeting "' . $meeting->title . '" with ' . ($meeting->student->name ?? 'student') . ' starts in 30 minutes at ' . $meeting->scheduled_at->format('h:i A') . '.',
                    'is_read' => false,
                ]);
            }

            $this->info('Sent reminders for meeting: ' . $meeting->title);
        }

        $this->info('Successfully sent ' . ($meetings->count() * 2) . ' meeting reminders.');
        return 0;
    }
}
