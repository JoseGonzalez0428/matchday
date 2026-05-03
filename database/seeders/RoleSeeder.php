<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'captain']);

        // Crear usuario administrador por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@matchday.test'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');
    }
}