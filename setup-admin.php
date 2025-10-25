<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Get admin users
$admins = DB::table('users')->where('role', 'admin')->get();

if ($admins->isEmpty()) {
    // Create admin user
    DB::table('users')->insert([
        'name' => 'Admin User',
        'email' => 'admin@studybuddy.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Admin user created!\n";
    echo "   Email: admin@studybuddy.com\n";
    echo "   Password: admin123\n";
} else {
    echo "✅ Admin users found:\n";
    foreach ($admins as $admin) {
        echo "   • " . $admin->name . " (" . $admin->email . ")\n";
    }
    
    // Update password for first admin
    DB::table('users')
        ->where('email', $admins[0]->email)
        ->update(['password' => Hash::make('admin123')]);
    
    echo "\n✅ Password updated to: admin123\n";
    echo "   Login with: " . $admins[0]->email . " / admin123\n";
}
