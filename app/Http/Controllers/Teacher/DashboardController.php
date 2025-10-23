<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();
        $totalStudents = User::role('student')->count();
        
        // Placeholder data - you can expand this later with actual task model
        $assignedTasks = 0;
        $upcomingMeetings = [];

        return view('teacher.dashboard', compact(
            'teacher',
            'totalStudents',
            'assignedTasks',
            'upcomingMeetings'
        ));
    }
}
