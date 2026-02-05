<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\SchoolRegistrationController;
use App\Http\Controllers\TeacherRegistrationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\ParentManagementController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\ReportCardController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    // Redirect authenticated users to their dashboard
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.schools'),
            'manager' => redirect()->route('manager.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => view('welcome'),
        };
    }
    return view('welcome');
});

// Public Registration Routes (No authentication required)
Route::get('/register/school', [SchoolRegistrationController::class, 'create'])->name('register.school');
Route::post('/register/school', [SchoolRegistrationController::class, 'store'])->name('register.school.store');
Route::get('/register/school/success', [SchoolRegistrationController::class, 'success'])->name('register.school.success');

Route::get('/register/teacher', [TeacherRegistrationController::class, 'create'])->name('register.teacher');
Route::post('/register/teacher', [TeacherRegistrationController::class, 'store'])->name('register.teacher.store');
Route::get('/register/teacher/success', [TeacherRegistrationController::class, 'success'])->name('register.teacher.success');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/schools', [AdminController::class, 'schools'])->name('schools');
    Route::get('/schools/create', [AdminController::class, 'createSchool'])->name('schools.create');
    Route::post('/schools', [AdminController::class, 'storeSchool'])->name('schools.store');

    // Pending school approvals
    Route::get('/schools/pending', [AdminController::class, 'pendingSchools'])->name('schools.pending');
    Route::post('/schools/{school}/approve', [AdminController::class, 'approveSchool'])->name('schools.approve');
    Route::post('/schools/{school}/reject', [AdminController::class, 'rejectSchool'])->name('schools.reject');
});

// Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
    Route::get('/link-parent', [ManagerController::class, 'linkParentToStudent'])->name('link-parent');
    Route::post('/link-parent', [ManagerController::class, 'storeParentLink'])->name('link-parent.store');

    // Edit and resubmit rejected schools
    Route::get('/school/edit', [ManagerController::class, 'editSchool'])->name('school.edit');
    Route::put('/school', [ManagerController::class, 'updateSchool'])->name('school.update');
    Route::post('/school/resubmit', [ManagerController::class, 'resubmitSchool'])->name('school.resubmit');

    // Teacher management
    Route::get('/teachers', [ManagerController::class, 'manageTeachers'])->name('teachers');
    Route::post('/teachers/assign', [ManagerController::class, 'assignTeacherToSchool'])->name('teachers.assign');
    Route::post('/teachers/assign-class', [ManagerController::class, 'assignTeacherToClassSubject'])->name('teachers.assign-class');
    Route::delete('/teachers/{teacherId}/unassign', [ManagerController::class, 'unassignTeacher'])->name('teachers.unassign');

    // Class management
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{id}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

    // Student management
    Route::get('/students', [StudentManagementController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentManagementController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentManagementController::class, 'store'])->name('students.store');
    Route::get('/students/{id}/edit', [StudentManagementController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentManagementController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentManagementController::class, 'destroy'])->name('students.destroy');

    // Parent management
    Route::get('/parents', [ParentManagementController::class, 'index'])->name('parents.index');
    Route::get('/parents/create', [ParentManagementController::class, 'create'])->name('parents.create');
    Route::post('/parents', [ParentManagementController::class, 'store'])->name('parents.store');
    Route::get('/parents/{id}/link-student', [ParentManagementController::class, 'linkStudent'])->name('parents.link-student');
    Route::post('/parents/{id}/link-student', [ParentManagementController::class, 'storeLinkStudent'])->name('parents.store-link-student');
    Route::delete('/parents/{parentId}/unlink/{studentId}', [ParentManagementController::class, 'unlinkStudent'])->name('parents.unlink-student');

    // Academic Terms management
    Route::get('/terms', [AcademicTermController::class, 'index'])->name('terms.index');
    Route::get('/terms/create', [AcademicTermController::class, 'create'])->name('terms.create');
    Route::post('/terms', [AcademicTermController::class, 'store'])->name('terms.store');
    Route::get('/terms/{id}/edit', [AcademicTermController::class, 'edit'])->name('terms.edit');
    Route::put('/terms/{id}', [AcademicTermController::class, 'update'])->name('terms.update');
    Route::delete('/terms/{id}', [AcademicTermController::class, 'destroy'])->name('terms.destroy');
    Route::post('/terms/{id}/set-current', [AcademicTermController::class, 'setCurrent'])->name('terms.set-current');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/grade-form/{classSubject}', [TeacherController::class, 'showGradeForm'])->name('grade-form');
    Route::post('/grades', [TeacherController::class, 'storeGrade'])->name('grades.store');

    // Attendance routes
    Route::get('/attendance', [TeacherController::class, 'attendanceClasses'])->name('attendance');
    Route::get('/attendance/{classId}', [TeacherController::class, 'showAttendanceSheet'])->name('attendance.sheet');
    Route::post('/attendance/{classId}', [TeacherController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance-history/{classId}', [TeacherController::class, 'attendanceHistory'])->name('attendance.history');

    // Messaging routes
    Route::get('/messages', [MessageController::class, 'teacherInbox'])->name('messages');
    Route::get('/messages/new', [MessageController::class, 'newConversation'])->name('messages.new');
    Route::get('/messages/{conversationId}', [MessageController::class, 'teacherConversation'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'sendMessage'])->name('messages.send');

    // Report Card routes
    Route::get('/report-card/{studentId}', [ReportCardController::class, 'generate'])->name('report-card.generate');
    Route::get('/report-card/{studentId}/preview', [ReportCardController::class, 'preview'])->name('report-card.preview');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');

    // Report card routes
    Route::get('/report-card-selector', function () {
        return view('student.report-selector');
    })->name('report-selector');

    Route::get('/report-card', function (Request $request) {
        $student = auth()->user()->student;
        $termId = $request->query('term'); // Get term from query parameter
        return app(ReportCardController::class)->generate($student->id, $termId);
    })->name('report-card');
});

Route::get('/report-card', function () {
    $student = auth()->user()->student;
    return app(ReportCardController::class)->generate($student->id);
})->name('report-card.download');

// Parent Routes
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [ParentController::class, 'viewAttendance'])->name('attendance');
    Route::get('/attendance/{studentId}', [ParentController::class, 'viewChildAttendance'])->name('attendance.child');

    // Messaging routes
    Route::get('/messages', [MessageController::class, 'parentInbox'])->name('messages');
    Route::get('/messages/new/{studentId}', [MessageController::class, 'newConversationParent'])->name('messages.new');
    Route::get('/messages/{conversationId}', [MessageController::class, 'parentConversation'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'sendMessage'])->name('messages.send');
});

// Announcements (accessible to managers, teachers, students, parents)
Route::middleware(['auth'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
});

// Notifications (accessible to all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/api/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
});

// Profile routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
