# EduTech Benin - Complete System Documentation

**Version:** 1.0  
**Last Updated:** December 30, 2025  
**Status:** Production Ready (80% Complete)

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Features by Role](#features-by-role)
3. [Routes Reference](#routes-reference)
4. [Database Schema](#database-schema)
5. [User Guide](#user-guide)
6. [Technical Architecture](#technical-architecture)
7. [Additional Features Roadmap](#additional-features-roadmap)

---

## System Overview

EduTech Benin is a comprehensive school management system built with Laravel 12, designed specifically for schools in Benin. It provides complete functionality for managing student records, attendance tracking, grade management, parent-teacher communication, and school administration.

### Key Statistics

- **Total Routes:** 45+
- **Database Tables:** 15+
- **User Roles:** 5 (Admin, Manager, Teacher, Student, Parent)
- **Controllers:** 10+
- **Views:** 35+
- **Features Implemented:** 20+

### Technology Stack

- **Framework:** Laravel 12
- **Authentication:** Laravel Breeze
- **Frontend:** Blade + Tailwind CSS
- **Database:** MySQL/PostgreSQL
- **Email:** Laravel Mail (log driver for development)

---

## Features by Role

### 1. Admin Features

**Dashboard:**
- System-wide statistics (Total schools, Active, Pending, Rejected)
- Quick access to pending approvals
- Professional gradient red header

**School Management:**
- View all schools in the system
- Approve pending school registrations
- Reject schools with custom reason
- Monitor school statuses

**Access:**
- Route: `/admin/schools`
- Pending approvals: `/admin/schools/pending`

---

### 2. Manager Features

**Dashboard:**
- School overview with gradient indigo header
- Quick action cards:
  - Manage Teachers
  - Link Parents
  - Announcements
  - School Info
- Statistics: Classes, Students, Teachers, Subjects
- Status alerts (Pending/Rejected/Active)

**School Registration:**
- Self-registration at `/register/school`
- Auto-generated manager account
- Email confirmation with credentials
- Edit & Resubmit if rejected

**Teacher Management:**
- View available teachers
- Assign teachers to school
- Assign to class + subject with coefficient
- Unassign functionality
- Route: `/manager/teachers`

**Parent Linking:**
- Link parents to students
- Create parent accounts
- Route: `/manager/link-parent`

**Announcements:**
- Create school-wide or class-specific announcements
- Priority levels (Urgent, High, Normal, Low)
- Full CRUD operations

---

### 3. Teacher Features

**Dashboard:**
- Welcome header with gradient blue
- Quick action cards:
  - Mark Attendance
  - Messages
  - Announcements
  - My Classes
- Class cards showing:
  - Subject name
  - Class name
  - Coefficient
  - Student count
  - Quick actions (Add Grades, Attendance)

**Attendance Management:**
- View all assigned classes
- Mark daily attendance (Present/Absent/Late/Excused)
- Bulk actions (Mark All Present/Absent)
- Add notes per student
- View 30-day history with statistics
- Routes:
  - `/teacher/attendance` - Class list
  - `/teacher/attendance/{classId}` - Mark attendance
  - `/teacher/attendance-history/{classId}` - View history

**Grade Management:**
- Add grades per student
- Two types: Control (10 points) and Exam (20 points)
- Benin formula: `((Avg Controls + Sum Exams) / 3) * Coefficient`
- Route: `/teacher/grade-form/{classSubject}`

**Messaging:**
- Send messages to parents
- View conversation history
- Student context maintained
- Unread indicators
- Routes:
  - `/teacher/messages` - Inbox
  - `/teacher/messages/new` - New message
  - `/teacher/messages/{id}` - Conversation

**Announcements:**
- Create announcements
- Target specific class or whole school
- Set priority levels

---

### 4. Student Features

**Dashboard:**
- Welcome header with gradient purple
- Overall average prominently displayed
- Quick action cards:
  - My Grades (with average)
  - My Attendance
  - Announcements
- Academic performance table:
  - Subject-wise grades
  - Controls average
  - Exams sum
  - Final grade with color coding
  - Coefficient
  - Teacher name

**Attendance Viewing:**
- Personal attendance records
- Statistics:
  - Attendance percentage
  - Present/Absent/Late/Excused counts
- Full history table
- Color-coded status indicators
- Route: `/student/attendance`

**Announcements:**
- View school and class-specific announcements
- Priority-based display

---

### 5. Parent Features

**Dashboard:**
- Welcome header with gradient pink
- Quick action cards:
  - Children's Grades
  - Attendance
  - Messages
  - Announcements
- Children overview cards showing:
  - Child's name and class
  - Overall average
  - Quick stats (Subjects, Grades, Status)
  - Status indicator (On Track / Needs Help)
  - Collapsible grade details
  - Action buttons

**Attendance Monitoring:**
- Overview of all children's attendance
- Circular progress indicators
- Individual child detailed view
- Route: `/parent/attendance`

**Messaging:**
- Message teachers about children
- View conversation history
- Grouped by child
- Routes:
  - `/parent/messages` - Inbox
  - `/parent/messages/new/{studentId}` - New message
  - `/parent/messages/{id}` - Conversation

**Announcements:**
- View announcements relevant to children

---

## Routes Reference

### Public Routes

```
GET  /                          Welcome page (redirects authenticated users)
GET  /register/school           School registration form
POST /register/school           Submit school registration
GET  /register/school-success   Registration success page
GET  /register/teacher          Teacher registration form
POST /register/teacher          Submit teacher registration
GET  /register/teacher-success  Teacher success page
```

### Admin Routes (`/admin`)

```
GET  /admin/schools             All schools list
GET  /admin/schools/pending     Pending schools for approval
POST /admin/schools/{id}/approve Approve school
POST /admin/schools/{id}/reject  Reject school
```

### Manager Routes (`/manager`)

```
GET  /manager/dashboard         Manager dashboard
GET  /manager/link-parent       Parent linking form
POST /manager/link-parent       Link parent to student
GET  /manager/school/edit       Edit rejected school
POST /manager/school/resubmit   Resubmit school for approval
GET  /manager/teachers          Teacher management
POST /manager/teachers/assign   Assign teacher to school
POST /manager/teachers/assign-class Assign to class-subject
POST /manager/teachers/{id}/unassign Unassign teacher
```

### Teacher Routes (`/teacher`)

```
GET  /teacher/dashboard         Teacher dashboard
GET  /teacher/grade-form/{id}   Add grades form
POST /teacher/grades            Store grades
GET  /teacher/attendance        Attendance classes list
GET  /teacher/attendance/{classId} Attendance sheet
POST /teacher/attendance/{classId} Mark attendance
GET  /teacher/attendance-history/{classId} Attendance history
GET  /teacher/messages          Messages inbox
GET  /teacher/messages/new      New message form
GET  /teacher/messages/{id}     View conversation
POST /teacher/messages          Send message
```

### Student Routes (`/student`)

```
GET  /student/dashboard         Student dashboard (grades)
GET  /student/attendance        View attendance
```

### Parent Routes (`/parent`)

```
GET  /parent/dashboard          Parent dashboard
GET  /parent/attendance         Children attendance overview
GET  /parent/attendance/{studentId} Child attendance details
GET  /parent/messages           Messages inbox
GET  /parent/messages/new/{studentId} New message
GET  /parent/messages/{id}      View conversation
POST /parent/messages           Send message
```

### Announcement Routes (Authenticated)

```
GET    /announcements           All announcements
GET    /announcements/create    Create form (Manager/Teacher)
POST   /announcements           Store announcement
GET    /announcements/{id}      View announcement
GET    /announcements/{id}/edit Edit form
PUT    /announcements/{id}      Update announcement
DELETE /announcements/{id}      Delete announcement
```

### Notification Routes (Authenticated)

```
GET  /notifications             All notifications
POST /notifications/{id}/read   Mark as read
POST /notifications/read-all    Mark all as read
GET  /api/notifications/count   Unread count (AJAX)
```

### Profile Routes (Authenticated)

```
GET    /profile        Edit profile
PATCH  /profile        Update profile
DELETE /profile        Delete account
```

---

## Database Schema

### Core Tables

**users**
- `id`, `name`, `email`, `password`
- `role` (enum: admin, manager, teacher, student, parent)
- `timestamps`

**schools**
- `id`, `name`, `address`, `phone`, `email`
- `status` (enum: pending, active, rejected)
- `rejection_reason`, `reviewed_at`, `reviewed_by`
- `timestamps`

**managers**
- `id`, `user_id`, `school_id`
- `timestamps`

**teachers**
- `id`, `user_id`, `specialization`
- `timestamps`

**teacher_school** (pivot)
- `id`, `teacher_id`, `school_id`
- `timestamps`

**classes**
- `id`, `name`, `school_id`
- `timestamps`

**subjects**
- `id`, `name`
- `timestamps`

**class_subject** (pivot)
- `id`, `class_id`, `subject_id`, `teacher_id`, `coefficient`
- `timestamps`

**students**
- `id`, `user_id`, `class_id`, `parent_id`
- `timestamps`

**parents**
- `id`, `user_id`
- `timestamps`

**grades**
- `id`, `student_id`, `class_subject_id`
- `type` (enum: control, exam)
- `score`, `date`
- `timestamps`

**attendances**
- `id`, `student_id`, `class_id`, `date`
- `status` (enum: present, absent, late, excused)
- `marked_by`, `notes`
- `timestamps`
- **Unique:** `student_id` + `date`

**conversations**
- `id`, `teacher_id`, `parent_id`, `student_id`
- `last_message_at`
- `timestamps`
- **Unique:** `teacher_id` + `parent_id` + `student_id`

**messages**
- `id`, `conversation_id`, `sender_id`
- `message`, `read_at`
- `timestamps`

**announcements**
- `id`, `school_id`, `author_id`
- `title`, `content`
- `target` (enum: all, specific_class)
- `target_class_id`
- `priority` (enum: low, normal, high, urgent)
- `published_at`
- `timestamps`

**notifications** (Laravel default)
- `id`, `type`, `notifiable_type`, `notifiable_id`
- `data`, `read_at`
- `timestamps`

---

## User Guide

### For School Managers

**1. Register Your School**
1. Visit `/register/school`
2. Fill in school information (name, address, phone, email)
3. Fill in your personal information (name, email)
4. Submit the form
5. Receive email with login credentials
6. Wait for admin approval

**2. After Approval**
1. Login with credentials
2. Access manager dashboard
3. Manage teachers:
   - Assign teachers to your school
   - Assign to specific classes and subjects
4. Link parents to students
5. Create announcements

**3. If Rejected**
1. Check dashboard for rejection reason
2. Click "Edit & Resubmit"
3. Fix the issues
4. Resubmit for approval

### For Teachers

**1. Register**
1. Visit `/register/teacher`
2. Fill in your information
3. Note your auto-generated password
4. Wait for manager to assign you

**2. Daily Workflow**
1. Login to dashboard
2. Mark attendance:
   - Click "Mark Attendance"
   - Select class
   - Mark each student
   - Save
3. Add grades:
   - Click on class card "Add Grades"
   - Select student
   - Enter grade (Control or Exam)
4. Check messages from parents
5. Create announcements

### For Students

**1. Login**
- Use credentials provided by school

**2. View Information**
1. Dashboard shows all grades
2. Click "My Attendance" to see records
3. Check announcements regularly

### For Parents

**1. Access**
- Credentials provided by school manager

**2. Monitor Children**
1. Dashboard shows all your children
2. Click child card to see detailed grades
3. Click "View Attendance" for attendance records
4. Message teachers about concerns
5. Check announcements

---

## Technical Architecture

### Authentication Flow

1. User registers (School/Teacher) or is created (Student/Parent)
2. Laravel Breeze handles authentication
3. Middleware checks role
4. Redirects to role-specific dashboard

### Grade Calculation

**Benin Formula:**
```php
final_grade = ((average_controls + sum_exams) / 3) * coefficient

Where:
- average_controls = sum(controls) / count(controls)
- sum_exams = sum(all exams)
- coefficient = from class_subject table
```

### Attendance Logic

- One record per student per day (unique constraint)
- Teacher can update same-day attendance
- Statistics calculated in real-time
- Notifications sent for absences

### Messaging System

- Conversations linked to specific student (context)
- One conversation per teacher-parent-student trio
- Unread tracking per user
- Real-time notification on new message

### Notification System

- Laravel's built-in notification system
- Database channel for web notifications
- Triggers:
  - New message received
  - (Future: Grade added, Attendance marked absent)

---

## Additional Features Roadmap

### Phase 4: Manager Tools (Not Implemented)

**4.1 Class Management**
- Create/edit/delete classes
- Assign students to classes
- View class rosters

**4.2 Subject Management**
- Add custom subjects
- Edit subject names
- Archive unused subjects

**4.3 Student Enrollment**
- Bulk student import (CSV)
- Individual student registration
- Student profile management
- Transfer students between classes

**4.4 Performance Analytics**
- Performance graphs (Chart.js)
- Class average comparisons
- Subject-wise analytics
- Trend analysis over time
- Export reports (PDF/Excel)

**4.5 Report Cards**
- Generate PDF report cards
- Include all subjects and grades
- Teacher comments
- Attendance summary
- Print-ready format

**4.6 Academic Terms**
- Define school terms/semesters
- Term-based grade calculation
- Historical data per term
- Year-end promotions

### Phase 5: Advanced Features (Future)

**5.1 Timetable Management**
- Create class schedules
- Teacher availability
- Room allocation
- Conflict detection

**5.2 Homework/Assignments**
- Teachers assign homework
- Students submit online
- Due dates and reminders
- Grade assignments

**5.3 Examination Management**
- Create exam schedules
- Seat allocation
- Result publication
- Mark sheet generation

**5.4 Fee Management**
- Fee structure setup
- Student fee records
- Payment tracking
- Receipts generation

**5.5 Library Management**
- Book catalog
- Issue/return tracking
- Student borrowing limits
- Late fee calculation

**5.6 Event Management**
- School events calendar
- Event registrations
- Photo galleries
- Announcements integration

**5.7 Mobile App**
- React Native app
- Push notifications
- Offline support
- Parent/Student access

**5.8 Advanced Reporting**
- Custom report builder
- Data export (Excel, CSV, PDF)
- Scheduled reports
- Dashboard analytics

**5.9 Integrations**
- SMS gateway for notifications
- Payment gateway (MTN Mobile Money, Moov Money)
- Cloud storage for documents
- Video conferencing (for online classes)

**5.10 Multi-language Support**
- French interface
- Local language options
- RTL support if needed

---

## Installation & Deployment

### Requirements

- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 15+
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

### Development Setup

```bash
# Clone repository
git clone <repository-url>
cd edutech_v0_v0

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run dev

# Start server
php artisan serve
```

### Production Deployment

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set up queue worker
php artisan queue:work --daemon

# Set up task scheduler (cron)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Environment Variables

```env
APP_NAME="EduTech Benin"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edutech
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@edutech.bj
MAIL_FROM_NAME="EduTech Benin"
```

---

## Security Considerations

### Implemented

- ✅ CSRF protection on all forms
- ✅ Role-based middleware
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ Authorization checks in controllers

### Recommended

- [ ] Rate limiting on public routes
- [ ] Two-factor authentication
- [ ] API authentication (Laravel Sanctum)
- [ ] Regular security audits
- [ ] Automated backups
- [ ] HTTPS enforcement
- [ ] Content Security Policy headers

---

## Maintenance

### Regular Tasks

**Daily:**
- Monitor error logs
- Check disk space

**Weekly:**
- Review new registrations
- Check system performance
- Test backup restoration

**Monthly:**
- Update dependencies
- Security patches
- Performance optimization
- User feedback review

**Quarterly:**
- Database optimization
- Code review
- Feature prioritization
- User training sessions

---

## Support & Contact

**Technical Support:**
- Email: support@edutech.bj
- Phone: +229 XXXX XXXX

**Documentation:**
- User Manual: Available in dashboard
- API Docs: `/api/documentation`
- Video Tutorials: Coming soon

---

## Changelog

### Version 1.0 (Current)

**Features:**
- Complete authentication system
- School registration & approval
- Teacher management
- Attendance tracking
- Grade management
- Parent-teacher messaging
- Announcements system
- Notification system
- Professional UI/UX

**Statistics:**
- 45+ routes
- 15+ database tables
- 35+ views
- 10+ controllers
- 20+ features

---

## License

Proprietary software - EduTech Benin
All rights reserved © 2025

---

**Document Version:** 1.0  
**Last Updated:** December 30, 2025  
**Next Review:** January 30, 2026
