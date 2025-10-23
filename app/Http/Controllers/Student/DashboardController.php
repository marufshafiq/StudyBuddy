<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        // Placeholder data - you can expand this later with actual task model
        $totalTasks = 0;
        $completedTasks = 0;
        $pendingTasks = 0;
        $assignedTasks = [];

        return view('student.dashboard', compact(
            'student',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'assignedTasks'
        ));
    }
}
