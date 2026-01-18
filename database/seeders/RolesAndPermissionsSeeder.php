<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create permissions
        $permissions = [
            'view assets',
            'create assets',
            'edit assets',
            'delete assets',
            'approve loans',
            'view loans',
            'create loans',
            'edit loans',
            'delete loans',
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',
            'delete_any_role',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles and assign existing permissions
        $peminjam = Role::create(['name' => 'Peminjam']);
        $peminjam->givePermissionTo([
            'view assets',
            'create loans',
            'view loans',
        ]);

        $operator = Role::create(['name' => 'Operator Ruangan']);
        $operator->givePermissionTo([
            'view assets',
            'edit assets',
            'view loans',
            'approve loans',
            'edit loans',
            'view rooms',
        ]);

        $admin = Role::create(['name' => 'Admin']);
        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());
    }
}
