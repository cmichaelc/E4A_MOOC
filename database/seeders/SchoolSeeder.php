<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create managers first
        $manager1 = User::create([
            'name' => 'Jean Kouassi',
            'email' => 'manager1@E4A_MOOC.bj',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'active',
        ]);

        $manager2 = User::create([
            'name' => 'Marie Adandé',
            'email' => 'manager2@E4A_MOOC.bj',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'active',
        ]);

        // Create schools
        School::create([
            'name' => 'École Primaire Cotonou',
            'status' => 'Active',
            'address' => '123 Avenue Steinmetz, Cotonou',
            'manager_id' => $manager1->id,
        ]);

        School::create([
            'name' => 'Lycée Technique Porto-Novo',
            'status' => 'Pending',
            'address' => '45 Rue des Martyrs, Porto-Novo',
            'manager_id' => $manager2->id,
        ]);
    }
}
