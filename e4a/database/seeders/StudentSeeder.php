<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\ParentModel;
use App\Models\ClassModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classes = ClassModel::all();
        $parents = ParentModel::all();

        $studentNames = [
            'Alice Agbangla',
            'Pierre Kponton',
            'Marie Djossou',
            'Lucas Zinsou',
            'Jeanne Hounkpatin',
            'David Akogbeto',
            'Sarah Gangbédji',
            'Emmanuel Métongnon',
            'Rita Assogba',
            'Yves Todjinou',
            'Nadège Koudé',
            'Armand Aïkpa',
            'Estelle Afouda',
            'Vincent Hodonou',
            'Sylvie Kindé',
        ];

        foreach ($studentNames as $index => $name) {
            // Create user account (MANDATORY)
            $user = User::create([
                'name' => $name,
                'email' => 'student' . ($index + 1) . '@edutech.bj',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
            ]);

            // Create student with mandatory user_id
            $student = Student::create([
                'user_id' => $user->id, // MANDATORY non-nullable
                'parent_id' => $parents->random()->id, // Link to random parent
                'class_id' => $classes->random()->id,
                'enrollment_date' => now()->subMonths(rand(1, 24)),
                'status' => 'active',
            ]);
        }

        // Create some siblings (2 students with same parent)
        $sharedParent = $parents->first();

        $sibling1User = User::create([
            'name' => 'Sophie Azonhimon',
            'email' => 'sibling1@edutech.bj',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $sibling1User->id,
            'parent_id' => $sharedParent->id,
            'class_id' => $classes->random()->id,
            'enrollment_date' => now()->subYear(),
            'status' => 'active',
        ]);

        $sibling2User = User::create([
            'name' => 'Marc Azonhimon',
            'email' => 'sibling2@edutech.bj',
            'password' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        Student::create([
            'user_id' => $sibling2User->id,
            'parent_id' => $sharedParent->id,
            'class_id' => $classes->random()->id,
            'enrollment_date' => now()->subYear(),
            'status' => 'active',
        ]);
    }
}
