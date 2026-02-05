<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\School;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        $classes = ClassModel::all();
        $subjects = Subject::all();

        // Create 5 teachers
        $teacherData = [
            ['name' => 'Robert Dossou', 'email' => 'teacher1@E4A_MOOC.bj', 'specialization' => 'Mathematics'],
            ['name' => 'Sophie Ahouandjinou', 'email' => 'teacher2@E4A_MOOC.bj', 'specialization' => 'Languages'],
            ['name' => 'Paul Akplogan', 'email' => 'teacher3@E4A_MOOC.bj', 'specialization' => 'Sciences'],
            ['name' => 'Claudine Zannou', 'email' => 'teacher4@E4A_MOOC.bj', 'specialization' => 'History'],
            ['name' => 'Jacques Hounkanrin', 'email' => 'teacher5@E4A_MOOC.bj', 'specialization' => 'General'],
        ];

        foreach ($teacherData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'status' => 'active',
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'specialization' => $data['specialization'],
                'phone' => '+229 ' . rand(90000000, 99999999),
            ]);

            // Assign each teacher to multiple schools
            $teacher->schools()->attach($schools->random(2)->pluck('id'), [
                'assigned_date' => now()->subMonths(rand(1, 6)),
            ]);

            // Assign teachers to class-subjects with coefficients
            $assignedClasses = $classes->random(rand(2, 4));
            foreach ($assignedClasses as $class) {
                $subjectsToAssign = $subjects->random(rand(1, 3));
                foreach ($subjectsToAssign as $subject) {
                    // Check if this subject is already assigned to this class
                    $existing = \DB::table('class_subject')
                        ->where('class_id', $class->id)
                        ->where('subject_id', $subject->id)
                        ->exists();

                    if (!$existing) {
                        \DB::table('class_subject')->insert([
                            'class_id' => $class->id,
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher->id,
                            'coefficient' => [1.0, 1.5, 2.0, 2.5][rand(0, 3)],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
