<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        $role = Role::create(['name' => 'dosen']);
        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'writer']);
        // $role1->givePermissionTo('edit articles');
        // $role1->givePermissionTo('delete articles');

        $role2 = Role::create(['name' => 'admin']);
        // $role2->givePermissionTo('publish articles');
        // $role2->givePermissionTo('unpublish articles');

        $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::create([
            'username' => "123",
            'password' => Hash::make('aaaaaaaa'),
            'name' => 'Dosen User',
        ]);
        $user->assignRole($role);

        $user = \App\Models\User::create([
            'username' => "asil",
            'password' => Hash::make('aaaaaaaa'),
            'name' => 'Asil',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::create([
            'username' => "usamah",
            'password' => Hash::make('aaaaaaaa'),
            'name' => 'Usamah',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::create([
            'username' => "alif",
            'password' => Hash::make('aaaaaaaa'),
            'name' => 'Alif',
        ]);
        $user->assignRole($role3);
    }
}
