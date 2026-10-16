<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create branches
        $centralWarehouse = Branch::create([
            'name' => 'Central Warehouse',
            'address' => 'Plot 45, Nyerere Road, Dar es Salaam',
            'phone' => '+255 22 211 0000',
            'email' => 'warehouse@kilimanjaroretail.co.tz',
        ]);

        $mlimaniBranch = Branch::create([
            'name' => 'Mlimani Branch',
            'address' => 'Mlimani City Mall, Dar es Salaam',
            'phone' => '+255 22 211 0001',
            'email' => 'mlimani@kilimanjaroretail.co.tz',
        ]);

        // Create Roles
        $roles = [
            'Super Admin',
            'Administrator',
            'Manager',
            'Cashier',
            'Inventory Officer',
            'Store Keeper',
            'Accountant',
            'Sales Officer',
            'Auditor',
            'Branch Manager'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create Super Admin User
        $superAdmin = User::create([
            'first_name' => 'Amina',
            'last_name' => 'Salum',
            'email' => 'admin@zpos.com',
            'phone' => '+255 754 000 000',
            'branch_id' => $centralWarehouse->id,
            'is_active' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $superAdmin->assignRole('Super Admin');
    }
}
