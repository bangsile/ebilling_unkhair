<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->role();
        $this->user();
    }

    public function user()
    {
        $user = \App\Models\User::create([
            'username' => "admindeveloper",
            'password' => Hash::make('admindev@2025'),
            'name' => 'Admin Developer',
        ]);

        $user->assignRole('admin');
    }

    public function role()
    {
        $role = Role::create(['name' => 'admin']);
    }
}
