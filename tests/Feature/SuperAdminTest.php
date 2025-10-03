<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_user_exists(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        
        $this->assertNotNull($user);
        $this->assertEquals('admin@gmail.com', $user->email);
        $this->assertEquals('Super Admin', $user->name);
        $this->assertEquals('User', $user->family);
    }

    public function test_super_admin_can_authenticate(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '1234Mm',
        ]);

        $response->assertRedirect('/');
    }

    public function test_super_admin_has_role(): void
    {
        $user = User::where('email', 'admin@gmail.com')->first();
        
        $this->assertTrue($user->hasRole('super-admin'));
    }
}
