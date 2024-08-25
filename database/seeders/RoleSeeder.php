<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\v1\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'permissions' => json_encode(['*']),
            'description' => 'Administrator',
        ]);
        Role::create([
        'name' => 'manager',
        'permissions' => json_encode(['*']),
        'description' => 'Manager',
        ]);
        Role::create([
            'name' => 'user',
            'permissions' => json_encode(['*']),
            'description' => 'User',
        ]);
    }
}
