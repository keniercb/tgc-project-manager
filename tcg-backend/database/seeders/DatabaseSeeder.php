<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $permissions = [
            'project.manage',
            'user.manage',
            'roles.manage',
            'permissions.manage',
            'artifacts.manage',
            'modules.manage',
            'modules.edit',
            'artifacts.view',
            'read_only'
        ];
        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminRole = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $adminRole->givePermissionTo(Permission::all());

        $pm = Role::query()->firstOrCreate(['name' => 'pm', 'guard_name' => 'api']);
        $pm->givePermissionTo(['artifacts.manage', 'modules.manage']);

        $engineer = Role::query()->firstOrCreate(['name' => 'engineer', 'guard_name' => 'api']);
        $engineer->givePermissionTo(['modules.edit', 'artifacts.view']);

        $viewer = Role::query()->firstOrCreate(['name' => 'viewer', 'guard_name' => 'api']);
        $viewer->givePermissionTo(['read_only']);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('1qazxsw2**'),
        ]);
        $admin->assignRole('admin');
    }
}
