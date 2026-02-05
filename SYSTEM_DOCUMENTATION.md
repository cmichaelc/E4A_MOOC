# E4A_MOOC Benin - Complete System Documentation

**Version:** Phase 1 & 2 Complete  
**Last Updated:** December 30, 2025  
**Server:** http://localhost:8000

---

## Table of Contents

1. [Public Routes](#public-routes)
2. [Admin Routes](#admin-routes)
3. [Manager Routes](#manager-routes)
4. [Teacher Routes](#teacher-routes)
5. [Student Routes](#student-routes)
6. [Parent Routes](#parent-routes)
7. [Controllers Overview](#controllers-overview)
8. [Views Directory Structure](#views-directory-structure)
9. [Database Schema](#database-schema)

---

## Public Routes

**Authentication:** None required (publicly accessible)

### Registration Routes

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/register/school` | GET | School registration form | `SchoolRegistrationController@create`<br>View: `register/school.blade.php`<br>Form for schools to self-register with manager details |
| `/register/school` | POST | Submit school registration | `SchoolRegistrationController@store`<br>Creates school + manager account, sends emails, status set to "Pending" |
| `/register/school/success` | GET | Registration success page | `SchoolRegistrationController@success`<br>View: `register/school-success.blade.php`<br>Shows next steps after registration |
| `/register/teacher` | GET | Teacher registration form | `TeacherRegistrationController@create`<br>View: `register/teacher.blade.php`<br>Form for teachers to self-register |
| `/register/teacher` | POST | Submit teacher registration | `TeacherRegistrationController@store`<br>Creates teacher account with auto-generated password |
| `/register/teacher/success` | GET | Teacher registration success | `TeacherRegistrationController@success`<br>View: `register/teacher-success.blade.php`<br>Displays login credentials |

### Authentication Routes

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/login` | GET | Login page | Laravel Breeze authentication<br>View: `auth/login.blade.php` |
| `/login` | POST | Process login | Authenticates user, redirects based on role |
| `/logout` | POST | Logout | Ends session, redirects to home |
| `/` | GET | Welcome/home page | View: `welcome.blade.php`<br>Landing page with registration links |

---

## Admin Routes

**Authentication:** Required | **Role:** admin  
**Middleware:** `auth`, `role:admin`  
**Prefix:** `/admin`

### School Management

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/admin/schools` | GET | List all schools | `AdminController@schools`<br>View: `admin/schools.blade.php`<br>Shows all active/rejected schools (excludes pending) |
| `/admin/schools/pending` | GET | Pending approvals | `AdminController@pendingSchools`<br>View: `admin/pending-schools.blade.php`<br>List of schools awaiting approval with Approve/Reject actions |
| `/admin/schools/{school}/approve` | POST | Approve school | `AdminController@approveSchool`<br>Sets status to "Active", records reviewer, sends approval email |
| `/admin/schools/{school}/reject` | POST | Reject school | `AdminController@rejectSchool`<br>Sets status to "Rejected", stores reason, sends rejection email |
| `/admin/schools/create` | GET | Create school form | `AdminController@createSchool`<br>View: `admin/create-school.blade.php` |
| `/admin/schools` | POST | Store new school | `AdminController@storeSchool`<br>Manual school creation by admin |

**Features:**
- ✅ View all schools with manager information
- ✅ Approve/reject pending registrations
- ✅ Add rejection reasons (required for reject)
- ✅ Email notifications sent automatically
- ✅ Review tracking (who approved/rejected, when)

---

## Manager Routes

**Authentication:** Required | **Role:** manager  
**Middleware:** `auth`, `role:manager`  
**Prefix:** `/manager`

### Dashboard & School Management

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/manager/dashboard` | GET | Manager dashboard | `ManagerController@dashboard`<br>View: `manager/dashboard.blade.php`<br>Shows school info, student count, quick actions |
| `/manager/school/edit` | GET | Edit rejected school | `ManagerController@editSchool`<br>View: `manager/edit-school.blade.php`<br>Only accessible if school status is "Rejected" |
| `/manager/school` | PUT | Update school details | `ManagerController@updateSchool`<br>Updates school name/address for rejected schools |
| `/manager/school/resubmit` | POST | Resubmit for approval | `ManagerController@resubmitSchool`<br>Resets status to "Pending", clears rejection data, notifies admin |

### Parent Management

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/manager/link-parent` | GET | Link parent form | `ManagerController@linkParentToStudent`<br>View: `manager/link-parent.blade.php`<br>Dropdown selection to link parents to students |
| `/manager/link-parent` | POST | Save parent link | `ManagerController@storeParentLink`<br>Updates student's parent_id field |

### Teacher Management

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/manager/teachers` | GET | Teacher management | `ManagerController@manageTeachers`<br>View: `manager/manage-teachers.blade.php`<br>Two-panel view: Available vs Assigned teachers |
| `/manager/teachers/assign` | POST | Assign teacher to school | `ManagerController@assignTeacherToSchool`<br>Adds teacher to school_teacher pivot table |
| `/manager/teachers/assign-class` | POST | Assign to class+subject | `ManagerController@assignTeacherToClassSubject`<br>Creates/updates ClassSubject record with teacher_id and coefficient |
| `/manager/teachers/{teacherId}/unassign` | DELETE | Remove teacher | `ManagerController@unassignTeacher`<br>Removes from school and all class assignments |

**Features:**
- ✅ View school status (Active/Pending/Rejected)
- ✅ Edit and resubmit if rejected
- ✅ Manage teacher assignments
- ✅ Assign teachers to class-subject combinations
- ✅ Set coefficients for subjects
- ✅ Link parents to students

---

## Teacher Routes

**Authentication:** Required | **Role:** teacher  
**Middleware:** `auth`, `role:teacher`  
**Prefix:** `/teacher`

### Dashboard & Grading

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/teacher/dashboard` | GET | Teacher dashboard | `TeacherController@dashboard`<br>View: `teacher/dashboard.blade.php`<br>Shows all assigned class-subjects |
| `/teacher/grade-form/{classSubject}` | GET | Grade entry form | `TeacherController@showGradeForm`<br>View: `teacher/grade-form.blade.php`<br>Form to add grades for students |
| `/teacher/grades` | POST | Save grade | `TeacherController@storeGrade`<br>Creates grade record (Control or Exam type) |

### Attendance Management

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/teacher/attendance` | GET | Attendance classes list | `TeacherController@attendanceClasses`<br>View: `teacher/attendance-classes.blade.php`<br>Shows all classes with today's marking status |
| `/teacher/attendance/{classId}` | GET | Attendance marking sheet | `TeacherController@showAttendanceSheet`<br>View: `teacher/attendance-sheet.blade.php`<br>Table with radio buttons for Present/Absent/Late/Excused |
| `/teacher/attendance/{classId}` | POST | Mark attendance | `TeacherController@markAttendance`<br>Creates/updates attendance records for all students |
| `/teacher/attendance-history/{classId}` | GET | Attendance history | `TeacherController@attendanceHistory`<br>View: `teacher/attendance-history.blade.php`<br>Last 30 days with statistics |

**Features:**
- ✅ View all assigned classes and subjects
- ✅ Enter grades (Controls and Exams)
- ✅ Mark daily attendance
- ✅ Bulk actions (Mark All Present/Absent)
- ✅ Add notes to attendance records
- ✅ View attendance history with statistics
- ✅ Update existing attendance

---

## Student Routes

**Authentication:** Required | **Role:** student  
**Middleware:** `auth`, `role:student`  
**Prefix:** `/student`

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/student/dashboard` | GET | Student dashboard | `StudentController@dashboard`<br>View: `student/dashboard.blade.php`<br>Shows all subjects with grades breakdown |
| `/student/attendance` | GET | My attendance | `StudentController@viewAttendance`<br>View: `student/attendance.blade.php`<br>Statistics cards + attendance history table |

**Features:**
- ✅ View all subject grades with calculations
- ✅ See Controls and Exams separately
- ✅ View final grade per subject
- ✅ View overall average
- ✅ View personal attendance records
- ✅ See attendance statistics (percentage, counts)
- ✅ View full attendance history

---

## Parent Routes

**Authentication:** Required | **Role:** parent  
**Middleware:** `auth`, `role:parent`  
**Prefix:** `/parent`

| URL | Method | Purpose | What's Behind It |
|-----|--------|---------|------------------|
| `/parent/dashboard` | GET | Parent dashboard | `ParentController@dashboard`<br>View: `parent/dashboard.blade.php`<br>Shows all children with their grades |
| `/parent/attendance` | GET | Children's attendance | `ParentController@viewAttendance`<br>View: `parent/attendance.blade.php`<br>Overview cards for all children |
| `/parent/attendance/{studentId}` | GET | Child attendance details | `ParentController@viewChildAttendance`<br>View: `parent/child-attendance.blade.php`<br>Full attendance view for one child |

**Features:**
- ✅ View all children's grades
- ✅ See each child's average
- ✅ View attendance overview for all children
- ✅ Circular progress indicators
- ✅ Drill down to individual child attendance
- ✅ Authorization checks (can only view own children)

---

## Controllers Overview

### AdminController
**Location:** `app/Http/Controllers/AdminController.php`

**Methods:**
- `schools()` - List all schools
- `pendingSchools()` - Get schools with "Pending" status
- `approveSchool($school)` - Approve pending school
- `rejectSchool(Request, $school)` - Reject with reason
- `createSchool()` - Show create form
- `storeSchool(Request)` - Create new school

### ManagerController
**Location:** `app/Http/Controllers/ManagerController.php`

**Methods:**
- `dashboard()` - Main dashboard
- `linkParentToStudent()` - Show parent link form
- `storeParentLink(Request)` - Save parent-student link
- `editSchool()` - Edit rejected school
- `updateSchool(Request)` - Update school details
- `resubmitSchool()` - Resubmit for approval
- `manageTeachers()` - Teacher assignment interface
- `assignTeacherToSchool(Request)` - Assign to school
- `assignTeacherToClassSubject(Request)` - Assign to class+subject
- `unassignTeacher($teacherId)` - Remove teacher

### TeacherController
**Location:** `app/Http/Controllers/TeacherController.php`

**Methods:**
- `dashboard()` - Shows assigned classes
- `showGradeForm($classSubjectId)` - Grade entry form
- `storeGrade(Request)` - Save grade
- `attendanceClasses()` - List classes for attendance
- `showAttendanceSheet($classId)` - Attendance marking form
- `markAttendance(Request, $classId)` - Save attendance
- `attendanceHistory($classId)` - View history

### StudentController
**Location:** `app/Http/Controllers/StudentController.php`

**Methods:**
- `dashboard()` - View grades
- `viewAttendance()` - View attendance with stats

### ParentController
**Location:** `app/Http/Controllers/ParentController.php`

**Methods:**
- `dashboard()` - View all children's grades
- `viewAttendance()` - Children attendance overview
- `viewChildAttendance($studentId)` - Individual child attendance

### SchoolRegistrationController
**Location:** `app/Http/Controllers/SchoolRegistrationController.php`

**Methods:**
- `create()` - Show registration form
- `store(Request)` - Process registration
- `success()` - Show success page

### TeacherRegistrationController
**Location:** `app/Http/Controllers/TeacherRegistrationController.php`

**Methods:**
- `create()` - Show registration form
- `store(Request)` - Process registration
- `success()` - Show credentials

---

## Views Directory Structure

```
resources/views/
├── auth/
│   └── login.blade.php              # Login page
├── layouts/
│   └── app.blade.php                # Main layout with navigation
├── welcome.blade.php                # Landing page
│
├── register/
│   ├── school.blade.php             # School registration form
│   ├── school-success.blade.php     # School registration success
│   ├── teacher.blade.php            # Teacher registration form
│   └── teacher-success.blade.php    # Teacher registration success
│
├── admin/
│   ├── schools.blade.php            # All schools list
│   ├── pending-schools.blade.php    # Pending approvals
│   └── create-school.blade.php      # Create school form
│
├── manager/
│   ├── dashboard.blade.php          # Manager dashboard
│   ├── edit-school.blade.php        # Edit rejected school
│   ├── link-parent.blade.php        # Link parent to student
│   ├── manage-teachers.blade.php    # Teacher assignment
│   └── no-school.blade.php          # No school assigned view
│
├── teacher/
│   ├── dashboard.blade.php          # Teacher dashboard
│   ├── grade-form.blade.php         # Grade entry form
│   ├── attendance-classes.blade.php # Classes list for attendance
│   ├── attendance-sheet.blade.php   # Attendance marking form
│   ├── attendance-history.blade.php # 30-day history view
│   └── no-profile.blade.php         # No teacher profile view
│
├── student/
│   ├── dashboard.blade.php          # Student grades view
│   ├── attendance.blade.php         # Student attendance view
│   └── no-profile.blade.php         # No student profile view
│
├── parent/
│   ├── dashboard.blade.php          # Children grades overview
│   ├── attendance.blade.php         # Children attendance overview
│   ├── child-attendance.blade.php   # Individual child attendance
│   └── no-profile.blade.php         # No parent profile view
│
└── emails/
    ├── school-registration-confirmation.blade.php  # Manager credentials
    ├── school-registration-notification.blade.php # Admin alert
    ├── school-approved.blade.php                  # Approval email
    └── school-rejected.blade.php                  # Rejection email
```

---

## Database Schema

### Core Tables

**users**
- Authentication table
- Fields: id, name, email, password, role (admin/manager/teacher/student/parent), status

**schools**
- Fields: id, name, address, status (Pending/Active/Rejected), manager_id
- Phase 1 additions: rejection_reason, reviewed_at, reviewed_by

**teachers**
- Fields: id, user_id, specialization, phone

**students**
- Fields: id, user_id, class_id, parent_id

**parents**
- Fields: id, user_id

**classes**
- Fields: id, name, school_id

**subjects**
- Fields: id, name

**class_subject** (pivot)
- Fields: id, class_id, subject_id, teacher_id, coefficient

**grades**
- Fields: id, student_id, class_subject_id, type (Control/Exam), score, date, academic_year

**attendances** (Phase 2)
- Fields: id, student_id, class_id, date, status (present/absent/late/excused), marked_by, notes
- Unique: (student_id, date)
- Indexes: (class_id, date)

**school_teacher** (pivot)
- Fields: id, school_id, teacher_id, assigned_date

---

## Feature Summary by Phase

### Phase 1: Registration & Approval ✅

**Implemented:**
- School manager self-registration
- Teacher self-registration
- Admin pending approvals interface
- Approve/reject with email notifications
- Manager edit & resubmit rejected schools
- Manager teacher assignment to school and classes
- Email notifications for all events

### Phase 2: Attendance System ✅

**Implemented:**
- Teacher attendance marking (all 4 statuses)
- Bulk actions (Mark All Present/Absent)
- Attendance notes support
- 30-day attendance history
- Student attendance view with statistics
- Parent children attendance overview
- Individual child attendance details
- Attendance percentage calculations
- Authorization checks

---

## Email Notifications

All emails use: `MAIL_MAILER=log` (development)

**Sent Emails:**
1. School registration confirmation (to manager) - with credentials
2. New school notification (to admin) - pending approval alert
3. School approved (to manager) - congratulations + login link
4. School rejected (to manager) - with rejection reason
5. School resubmitted (to admin) - notification of resubmission

---

## Access Control Summary

| Route Pattern | Admin | Manager | Teacher | Student | Parent | Public |
|--------------|-------|---------|---------|---------|--------|--------|
| `/` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `/register/*` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `/login` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `/admin/*` | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| `/manager/*` | ✗ | ✓ | ✗ | ✗ | ✗ | ✗ |
| `/teacher/*` | ✗ | ✗ | ✓ | ✗ | ✗ | ✗ |
| `/student/*` | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ |
| `/parent/*` | ✗ | ✗ | ✗ | ✗ | ✓ | ✗ |

---

## Navigation Links by Role

### Admin Navigation
- Schools → `/admin/schools`
- Pending Approvals → `/admin/schools/pending`

### Manager Navigation
- Dashboard → `/manager/dashboard`
- Manage Teachers → `/manager/teachers`
- Link Parent → `/manager/link-parent`

### Teacher Navigation
- My Classes → `/teacher/dashboard`
- Attendance → `/teacher/attendance`

### Student Navigation
- My Grades → `/student/dashboard`
- My Attendance → `/student/attendance`

### Parent Navigation
- Children's Grades → `/parent/dashboard`
- Attendance → `/parent/attendance`

---

## Quick Reference

### Test Credentials

```
Admin:       admin@E4A_MOOC.bj / password
Manager 1:   manager1@E4A_MOOC.bj / password
Manager 2:   manager2@E4A_MOOC.bj / password
Teacher:     testteacher@example.com / EuKwiqqrAp
```

### Common Database Queries

```bash
# View all routes
php artisan route:list

# Check school statuses
App\Models\School::select('name', 'status')->get();

# Check attendance records
App\Models\Attendance::whereDate('date', today())->count();

# View teacher assignments
App\Models\ClassSubject::with(['teacher.user', 'class', 'subject'])->get();
```

---

## What's Next

**Phase 3: Messaging & Notifications** (Planned)
- Internal messaging system
- Email notifications for absences
- Announcement system

**Phase 4: Advanced Features** (Planned)
- Analytics and reporting
- Attendance reports for managers
- Export functionality (Excel/PDF)
- Calendar views
- Mobile optimization

---

## Support & Documentation

- Laravel Version: 12.x
- PHP Version: 8.2+
- Database: MySQL/MariaDB
- Frontend: Blade Templates + Tailwind CSS
- Authentication: Laravel Breeze

**For development questions:**
- Check `routes/web.php` for all route definitions
- Controllers are in `app/Http/Controllers/`
- Views are in `resources/views/`
- Models are in `app/Models/`

---

*Last Updated: December 30, 2025*  
*Phases 1 & 2 Complete and Tested*
