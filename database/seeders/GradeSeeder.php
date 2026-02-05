<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\ClassSubject;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::with('class')->get();
        $academicYear = '2024-2025';

        foreach ($students as $student) {
            if (!$student->class)
                continue;

            // Get class-subjects for this student's class
            $classSubjects = ClassSubject::where('class_id', $student->class_id)->get();

            foreach ($classSubjects as $classSubject) {
                // Create 2-3 control grades per subject
                $numControls = rand(2, 3);
                for ($i = 0; $i < $numControls; $i++) {
                    Grade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'type' => 'Control',
                        'score' => rand(8, 18) + (rand(0, 99) / 100), // Random score between 8-18
                        'date' => now()->subDays(rand(10, 60)),
                        'academic_year' => $academicYear,
                    ]);
                }

                // Create 1-2 exam grades per subject
                $numExams = rand(1, 2);
                for ($i = 0; $i < $numExams; $i++) {
                    Grade::create([
                        'student_id' => $student->id,
                        'class_subject_id' => $classSubject->id,
                        'type' => 'Exam',
                        'score' => rand(9, 19) + (rand(0, 99) / 100), // Random score between 9-19
                        'date' => now()->subDays(rand(5, 30)),
                        'academic_year' => $academicYear,
                    ]);
                }
            }
        }
    }
}
