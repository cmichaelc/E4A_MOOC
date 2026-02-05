<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\School;
use App\Models\Grade;
use App\Models\ClassSubject;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;

class TestStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a school
        $school = School::first();
        if (!$school) {
            $school = School::create([
                'name' => 'Test School',
                'address' => 'Test Address',
                'status' => 'active',
            ]);
        }

        // Get or create a class
        $class = ClassModel::where('school_id', $school->id)->first();
        if (!$class) {
            $class = ClassModel::create([
                'school_id' => $school->id,
                'name' => '6ème A',
                'academic_year' => '2024-2025',
            ]);
        }

        // Create test student user
        $user = User::where('email', 'student@test.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Jean Dupont',
                'email' => 'student@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'class_id' => $class->id,
            ]);

            // Add some sample grades if class has subjects
            $classSubjects = $class->classSubjects;
            if ($classSubjects->count() > 0) {
                foreach ($classSubjects as $classSubject) {
                    // Add 2 control grades
                    Grade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'type' => 'control',
                        'score' => rand(10, 18),
                        'academic_year' => '2024-2025',
                        'date' => now()->subDays(rand(1, 30)),
                    ]);

                    Grade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'type' => 'control',
                        'score' => rand(10, 19),
                        'academic_year' => '2024-2025',
                        'date' => now()->subDays(rand(1, 30)),
                    ]);

                    // Add 1 exam grade
                    Grade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'type' => 'exam',
                        'score' => rand(8, 20),
                        'academic_year' => '2024-2025',
                        'date' => now()->subDays(rand(1, 20)),
                    ]);
                }
            }

            // Add some attendance records
            for ($i = 0; $i < 20; $i++) {
                $statuses = ['present', 'present', 'present', 'absent', 'late'];
                Attendance::create([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'date' => now()->subDays($i),
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }

            $this->command->info('✅ Test student created with grades and attendance!');
            $this->command->info('   Email: student@test.com');
            $this->command->info('   Password: password');
        } else {
            $this->command->info('✓ Test student already exists');
        }
    }
}
