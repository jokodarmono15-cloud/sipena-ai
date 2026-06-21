<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_attendance',
            'create_attendance',
            'approve_attendance',
            'view_wfa',
            'create_wfa',
            'approve_wfa',
            'view_leave',
            'create_leave',
            'approve_leave',
            'manage_users',
            'manage_settings',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $headmasterRole = Role::firstOrCreate(['name' => 'kepala_sekolah']);
        $teacherRole = Role::firstOrCreate(['name' => 'guru']);

        // Assign permissions to admin
        $adminRole->syncPermissions($permissions);

        // Assign permissions to headmaster
        $headmasterRole->syncPermissions([
            'view_attendance',
            'approve_attendance',
            'view_wfa',
            'approve_wfa',
            'view_leave',
            'approve_leave',
            'view_reports',
        ]);

        // Assign permissions to teacher
        $teacherRole->syncPermissions([
            'view_attendance',
            'create_attendance',
            'view_wfa',
            'create_wfa',
            'view_leave',
            'create_leave',
        ]);
    }
}
