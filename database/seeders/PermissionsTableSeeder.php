<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Permission::create([
            'name' => 'manage_users',
            'description' => 'Permission to manage users',
        ]);

        \App\Models\Permission::create([
            'name' => 'manage_roles',
            'description' => 'Permission to manage roles',
        ]);
    }
}
