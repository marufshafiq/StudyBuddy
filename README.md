# 📚 StudyBuddy - Student & Teacher Management System

A comprehensive web application built with Laravel for managing students, teachers, tasks, meetings, and educational resources. Features role-based access control with dedicated dashboards for students, teachers, and administrators.

## 🎯 Features

### 👨‍🎓 Student Dashboard
- View and manage assigned tasks
- Submit task solutions
- Request meetings with teachers
- Save and favorite books from OpenLibrary (20M+ books)
- Read books online
- Receive notifications for tasks, meetings, and reminders

### 👨‍🏫 Teacher Dashboard
- Create and assign tasks to students
- Review submitted tasks and provide feedback
- Approve/reject meeting requests
- Schedule Zoom meetings automatically
- Manage meeting schedules
- Send notifications to students

### 👑 Admin Dashboard
- **User Management**: View all users (students, teachers, admins)
- **Teacher Statistics**: Track tasks created, meetings hosted, and upcoming schedules
- **Student Statistics**: Monitor task completion, meeting attendance, and saved resources
- **Task Oversight**: View who assigned tasks to whom, track status (pending, in progress, completed)
- **Meeting Management**: See all meetings including:
  - All meetings with status
  - Pending meeting requests
  - Rejected meetings with reasons
  - Zoom meeting links
- **Notifications**: System-wide notification history
- **Resources**: All books saved by students across the platform
- **Statistics**: Comprehensive analytics and 7-day growth trends
- **User Details**: Deep dive into individual user profiles and activities

### 🔐 Authentication & Authorization
- Role-based access control (Student, Teacher, Admin)
- Custom middleware for each role
- Automatic role-based redirection after login
- Secure authentication with Laravel Breeze

### 📅 Meeting Management
- Zoom API integration with Server-to-Server OAuth
- Automatic meeting link generation
- Meeting request/approve/reject workflow
- Meeting reminders (30 minutes before via scheduler)
- Meeting status tracking (scheduled, completed, cancelled)

### 📚 Resource Library (OpenLibrary Integration)
- Search 20+ million books
- Browse by 15 subject categories
- Save books to personal library
- Mark books as favorites
- Read books online (when available)
- View book details (authors, ISBN, publish year, subjects)

### 🔔 Notification System
- Real-time notifications for tasks, meetings, and reminders
- Meeting reminders sent 30 minutes before scheduled time
- Task assignment notifications
- Meeting approval/rejection notifications

## 🛠️ Technology Stack

- **Framework**: Laravel 12.35.1
- **PHP**: 8.2.12
- **Database**: SQLite
- **Frontend**: TailwindCSS (CDN), Blade Templates
- **APIs**: 
  - Zoom API (Server-to-Server OAuth)
  - OpenLibrary API (free, no API key required)
- **Task Scheduler**: Laravel Scheduler for meeting reminders

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM (for frontend assets)
- Zoom Server-to-Server OAuth credentials (for meetings)

## 🚀 Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd StudyBuddy
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Create environment file**
```bash
copy .env.example .env
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Configure database**
The application uses SQLite by default. Ensure the database file exists:
```bash
touch database/database.sqlite
```

7. **Configure Zoom API** (Optional - for meeting features)
Add your Zoom credentials to `.env`:
```env
ZOOM_ACCOUNT_ID=your_account_id
ZOOM_CLIENT_ID=your_client_id
ZOOM_CLIENT_SECRET=your_client_secret
```

See `ZOOM_API_SETUP.md` for detailed setup instructions.

8. **Run migrations**
```bash
php artisan migrate
```

9. **Start the development server**
```bash
php artisan serve
```

10. **Start the task scheduler** (for meeting reminders)
```bash
php artisan schedule:work
```

## 👥 Default Credentials

### Admin Account
- **Email**: admin@studybuddy.com
- **Password**: admin123

To create additional admin users or reset password, run:
```bash
php artisan tinker setup-admin.php
```

### Creating Test Users
Register new users through the registration page and select the appropriate role (Student or Teacher).

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminDashboardController.php    # Admin functionality
│   │   ├── StudentDashboardController.php  # Student functionality
│   │   └── TeacherDashboardController.php  # Teacher functionality
│   └── Middleware/
│       ├── RedirectIfNotAdmin.php
│       ├── RedirectIfNotStudent.php
│       └── RedirectIfNotTeacher.php
├── Models/
│   ├── User.php
│   ├── Task.php
│   ├── Meeting.php
│   ├── Notification.php
│   └── Resource.php
└── Services/
    ├── ZoomService.php         # Zoom API integration
    └── OpenLibraryService.php  # OpenLibrary API integration

resources/views/
├── admin/          # Admin dashboard views
├── student/        # Student dashboard views
├── teacher/        # Teacher dashboard views
└── layouts/
    ├── admin.blade.php
    ├── student.blade.php
    └── teacher.blade.php

routes/
├── web.php         # Public routes
├── auth.php        # Authentication routes
├── student.php     # Student routes (protected)
├── teacher.php     # Teacher routes (protected)
└── admin.php       # Admin routes (protected)
```

## 🎨 Key Features Explained

### Task Management
- Teachers create tasks with title, description, and deadline
- Students receive notifications and can view tasks in their dashboard
- Students can update task status (pending → in progress → submitted)
- Teachers can review submissions and mark as completed

### Meeting System
1. Student requests a meeting with a teacher
2. Teacher receives notification and can approve/reject
3. If approved, Zoom meeting is automatically created via API
4. Meeting link is shared with both parties
5. 30 minutes before meeting, both receive reminder notifications
6. After meeting, status can be updated to completed

### Resource Library
- Powered by OpenLibrary's free API (20M+ books)
- Search by keyword or browse by subject
- Each book shows cover image, title, authors, and publication info
- Save books to personal library
- Star books as favorites
- Read online button (when full-text available)

### Admin Oversight
- Complete visibility into all system activities
- View all users with detailed statistics
- Track task assignments (who assigned to whom)
- Monitor all meetings including rejected ones with reasons
- View all notifications sent across the system
- See all books saved by students
- Comprehensive analytics dashboard with growth trends

## 🔧 Configuration

### Zoom Meeting Integration
Create a Server-to-Server OAuth app in Zoom Marketplace and add credentials to `.env`. See `ZOOM_API_SETUP.md` for detailed instructions.

### OpenLibrary Integration
No API key required! The OpenLibrary API is completely free. See `OPENLIBRARY_INTEGRATION.md` for API details and examples.

### Task Scheduler
To enable automatic meeting reminders, run the scheduler:
```bash
php artisan schedule:work
```

Or add to cron (production):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🌐 Routes

### Public Routes
- `/` - Welcome page
- `/login` - Login page
- `/register` - Registration page

### Student Routes (Protected)
- `/student/dashboard` - Student dashboard
- `/student/tasks` - Task management
- `/student/meetings` - Meeting requests
- `/student/resources` - Book library

### Teacher Routes (Protected)
- `/teacher/dashboard` - Teacher dashboard
- `/teacher/tasks` - Task creation & management
- `/teacher/meetings` - Meeting approval

### Admin Routes (Protected)
- `/admin/dashboard` - Admin overview
- `/admin/users` - All users
- `/admin/teachers` - Teacher list
- `/admin/students` - Student list
- `/admin/tasks` - All tasks
- `/admin/meetings` - All meetings
- `/admin/meetings/pending` - Pending requests
- `/admin/meetings/rejected` - Rejected meetings
- `/admin/notifications` - All notifications
- `/admin/resources` - All saved books
- `/admin/stats` - System statistics

## 📊 Database Schema

- **users**: User accounts with role-based access
- **tasks**: Task assignments between teachers and students
- **meetings**: Meeting requests and schedules with Zoom integration
- **notifications**: System notifications for all users
- **resources**: Saved books from OpenLibrary

## 🔒 Security Features

- Role-based middleware protection
- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention with Eloquent ORM
- XSS protection in Blade templates

## 📝 API Documentation

### Zoom API
See `ZOOM_API_SETUP.md` for:
- Server-to-Server OAuth setup
- Meeting creation API
- Error handling

### OpenLibrary API
See `OPENLIBRARY_INTEGRATION.md` for:
- Search endpoints
- Subject browsing
- Book details
- Cover images
- Online reader links

## 🐛 Troubleshooting

**Scheduler not working?**
- Ensure `php artisan schedule:work` is running
- Check logs in `storage/logs/laravel.log`

**Zoom meetings not creating?**
- Verify Zoom credentials in `.env`
- Check Zoom app scopes include `meeting:write`
- Review error logs

**Database errors?**
- Run `php artisan migrate:fresh` to reset database
- Check SQLite file permissions

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer

Built with ❤️ using Laravel

---

For more information about Laravel, visit the [Laravel documentation](https://laravel.com/docs).

