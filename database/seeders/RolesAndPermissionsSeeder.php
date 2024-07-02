<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Define the permissions
        $permissions = ['view_profile', 'update_profile', 'reset_password'];

        // Define permissions for each role
        $permissionsByRole = [
            'admin' => $permissions,
            'user' => $permissions,
        ];

        // Insert permissions into the database if they don't already exist
        foreach ($permissionsByRole as $role => $permissionNames) {
            foreach ($permissionNames as $name) {
                $existingPermission = Permission::where('name', $name)->where('guard_name', 'web')->first();

                if (!$existingPermission) {
                    // Permission doesn't exist, insert it
                    Permission::create([
                        'name' => $name,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }

        // Associate permissions with roles
        foreach ($permissionsByRole as $role => $permissionNames) {
            $roleModel = Role::whereName($role)->first();

            if ($roleModel) {
                $permissionModels = Permission::whereIn('name', $permissionNames)->where('guard_name', 'web')->get();

                $roleModel->givePermissionTo($permissionModels);
            } else {
                // Role doesn't exist, you may want to create it
                Role::create(['name' => $role, 'guard_name' => 'web'])
                    ->givePermissionTo($permissionNames);
            }
        }
    }
}
