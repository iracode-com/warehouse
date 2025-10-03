<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-super-admin {--email=admin@gmail.com} {--password=1234Mm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user with full permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'family' => 'User',
                'email' => $email,
                'mobile' => '09123456789',
                'username' => 'admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        // Create super admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
            ]
        );

        // Assign all permissions to super admin role
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Assign super admin role to the user
        $superAdmin->assignRole($superAdminRole);

        $this->info('Super Admin user created successfully!');
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
    }
}
