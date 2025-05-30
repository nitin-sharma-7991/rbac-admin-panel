<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create([
            'name' => 'Admin',
            'description' => 'Administrator role with full access',
        ]);

        \App\Models\Role::create([
            'name' => 'User',
            'description' => 'General user role with limited access',
        ]);
    }
}
