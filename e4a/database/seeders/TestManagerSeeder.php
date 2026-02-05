<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;

class TestManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if test manager exists
        $user = User::where('email', 'manager@benin-school.bj')->first();

        if (!$user) {
            // Create user
            $user = User::create([
                'name' => 'Test Manager',
                'email' => 'manager@benin-school.bj',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ]);
        }

        // Check if manager profile exists
        $manager = Manager::where('user_id', $user->id)->first();

        if (!$manager) {
            // Check if school exists
            $school = School::where('name', 'École Primaire de Benin')->first();

            if (!$school) {
                // Create school (only using existing columns: name, address, status)
                $school = School::create([
                    'name' => 'École Primaire de Benin',
                    'address' => 'Cotonou, Benin',
                    'status' => 'active',
                ]);
            }

            // Create manager profile
            Manager::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
            ]);

            $this->command->info('✅ Test manager profile created and linked to school!');
        } else {
            $this->command->info('✓ Test manager already exists');
        }
    }
}
