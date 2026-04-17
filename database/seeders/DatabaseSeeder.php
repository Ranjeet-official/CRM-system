<?php

namespace Database\Seeders;

use App\Models\Client;
// use App\Models\Project;
// use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Permissions
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manager activity']);
        Permission::firstOrCreate(['name' => 'delete']);
        Permission::firstOrCreate(['name' => 'add task']);

        // ✅ Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        $adminRole->givePermissionTo(['manage users', 'delete','add task','manager activity']);

        // ✅ Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Demo',
                'password' => bcrypt('password123'),
            ]
        );
        $admin->assignRole('admin');


        User::factory()->count(10)->create();
        // Client::factory()->count(30)->create();
        // Project::factory()->count(15)->create();
        // Task::factory()->count(68)->create();
    }
}
