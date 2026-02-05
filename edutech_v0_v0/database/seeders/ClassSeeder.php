<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\School;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();
        $academicYear = '2024-2025';

        foreach ($schools as $school) {
            // Create 3 classes per school
            ClassModel::create([
                'school_id' => $school->id,
                'name' => 'Class 6A',
                'academic_year' => $academicYear,
                'level' => '6th Grade',
            ]);

            ClassModel::create([
                'school_id' => $school->id,
                'name' => 'Class 6B',
                'academic_year' => $academicYear,
                'level' => '6th Grade',
            ]);

            ClassModel::create([
                'school_id' => $school->id,
                'name' => 'Class 7A',
                'academic_year' => $academicYear,
                'level' => '7th Grade',
            ]);
        }
    }
}
