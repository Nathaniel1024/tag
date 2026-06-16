<?php

namespace Tests\Feature;

use App\Models\BarangayOfficer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_email_only(): void
    {
        BarangayOfficer::create([
            'fullname' => 'Admin One',
            'email' => 'admin@example.com',
            'username' => 'adminone',
            'password' => Hash::make('secret123'),
            'contact' => '09123456789',
            'address' => 'Main Office',
            'role' => 'admin',
        ]);

        $response = $this->post('/loginadmin', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ], ['Accept' => 'application/json']);

        $response->assertOk()
            ->assertJsonPath('user.email', 'admin@example.com')
            ->assertJsonPath('user.role', 'admin');
    }

    public function test_admin_login_rejects_username_only_input(): void
    {
        $response = $this->post('/loginadmin', [
            'email' => 'adminone',
            'password' => 'secret123',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
    }
}
