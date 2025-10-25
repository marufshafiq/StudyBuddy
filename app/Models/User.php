<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the tasks assigned to the user (as student).
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'student_id');
    }

    /**
     * Get the tasks assigned to the user (for admin view - as student).
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'student_id');
    }

    /**
     * Get the tasks created by the user (as teacher).
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'teacher_id');
    }

    /**
     * Get the meetings for the user (as student).
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'student_id');
    }

    /**
     * Get the meetings for the user (as student - for admin view).
     */
    public function studentMeetings()
    {
        return $this->hasMany(Meeting::class, 'student_id');
    }

    /**
     * Get the meetings hosted by the user (as teacher).
     */
    public function teacherMeetings()
    {
        return $this->hasMany(Meeting::class, 'teacher_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get the resources saved by the user.
     */
    public function savedResources()
    {
        return $this->hasMany(Resource::class, 'user_id');
    }
}

