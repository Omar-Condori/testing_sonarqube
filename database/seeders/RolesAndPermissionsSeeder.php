<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'user_create',
            'user_read',
            'user_update',
            'user_delete',
            'role_create',
            'role_read',
            'role_update',
            'role_delete',
            'permission_read',
            'permission_assign',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Crear roles y asignar permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $operatorRole = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operatorRole->syncPermissions([
            'user_read', 'role_read', 'permission_read'
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Crear usuario admin solo si no existe
        $admin = User::firstOrCreate(
            ['email' => 'admin@turismo.com'],
            [
                'name' => 'Admin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}