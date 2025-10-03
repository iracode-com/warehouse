<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('roles')->count() == 0) {
            $this->command->info('Generating Shield Policies ...');
            Artisan::call("shield:generate", [
                '--all' => true,
                '--panel' => 'admin',
                '--option' => 'all'
            ]);
            $this->command->info('All Shield Policies Generated Successfully!');
        }

        // Create super admin user
        $pass = '1234Mm';
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'family' => 'User',
                'email' => 'admin@gmail.com',
                'mobile' => '09123456789',
                'username' => 'admin',
                'password' => Hash::make($pass),
                'email_verified_at' => now(),
            ]
        );

        // Create super admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'name' => 'super_admin',
                'guard_name' => 'web',
            ]
        );

        // Assign all permissions to super admin role
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Assign super admin role to the user
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: ' . $pass);
    }
}
