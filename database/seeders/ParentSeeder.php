<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        $parentData = [
            ['name' => 'André Koffi', 'email' => 'parent1@E4A_MOOC.bj', 'occupation' => 'Engineer'],
            ['name' => 'Françoise Danbé', 'email' => 'parent2@E4A_MOOC.bj', 'occupation' => 'Doctor'],
            ['name' => 'Michel Soglo', 'email' => 'parent3@E4A_MOOC.bj', 'occupation' => 'Teacher'],
            ['name' => 'Cécile Azonhimon', 'email' => 'parent4@E4A_MOOC.bj', 'occupation' => 'Businesswoman'],
            ['name' => 'Thomas Gbedo', 'email' => 'parent5@E4A_MOOC.bj', 'occupation' => 'Civil Servant'],
            ['name' => 'Pauline Ahossi', 'email' => 'parent6@E4A_MOOC.bj', 'occupation' => 'Nurse'],
            ['name' => 'Daniel Assogba', 'email' => 'parent7@E4A_MOOC.bj', 'occupation' => 'Merchant'],
        ];

        foreach ($parentData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'parent',
                'status' => 'active',
            ]);

            ParentModel::create([
                'user_id' => $user->id,
                'phone' => '+229 ' . rand(90000000, 99999999),
                'address' => 'Cotonou, Benin',
                'occupation' => $data['occupation'],
            ]);
        }
    }
}
