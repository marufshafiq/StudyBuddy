<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTeachers = User::role('teacher')->count();
        $totalStudents = User::role('student')->count();
        $totalAdmins = User::role('admin')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTeachers',
            'totalStudents',
            'totalAdmins'
        ));
    }
}
