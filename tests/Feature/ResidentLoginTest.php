<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_login_with_email_only(): void
    {
        $this->withoutMiddleware();

        $user = User::create([
            'name' => 'Jane Dela Cruz',
            'username' => 'janed',
            'fullname' => 'Jane Dela Cruz',
            'email' => 'jane@example.com',
            'password' => Hash::make('secret123'),
            'age' => 28,
            'contact' => '09123456789',
            'address' => '123 Main St',
            'role' => 'resident',
        ]);

        Resident::create([
            'fullname' => 'Jane Dela Cruz',
            'email' => 'jane@example.com',
            'contact' => '09123456789',
            'age' => 28,
            'address' => '123 Main St',
        ]);

        $response = $this->postJson('/resident/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', 'jane@example.com')
            ->assertJsonPath('user.username', 'janed');
    }

    public function test_resident_login_rejects_username_only_input(): void
    {
        $this->withoutMiddleware();

        $response = $this->postJson('/resident/login', [
            'email' => 'janed',
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);
    }

}
