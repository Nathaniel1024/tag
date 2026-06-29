<?php

namespace Tests\Feature;

use App\Models\ResidentRegistrationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ResidentRegistrationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_registration_stays_pending_until_approved(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/resident/register', [
            'username' => 'juan.d',
            'email' => 'juan@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'contact' => '09123456789',
            'age' => 28,
            'address' => '123 Main St',
            'profile_image' => UploadedFile::fake()->create('id.jpg', 100, 'image/jpeg'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('request.email', 'juan@example.com')
            ->assertJsonPath('request.status', 'pending');

        $this->assertDatabaseHas('resident_registration_requests', [
            'email' => 'juan@example.com',
            'status' => 'pending',
        ]);

        $login = $this->postJson('/resident/login', [
            'email' => 'juan@example.com',
            'password' => 'secret123',
        ]);

        $login->assertStatus(403)
            ->assertJsonPath('message', 'Your registration is still pending approval.');
    }

    public function test_approved_resident_can_login_and_declined_resident_cannot(): void
    {
        $this->withoutMiddleware();

        $this->post('/resident/register', [
            'username' => 'maria.d',
            'email' => 'maria@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'first_name' => 'Maria',
            'middle_name' => 'Reyes',
            'last_name' => 'Dela Cruz',
            'contact' => '09123456789',
            'age' => 30,
            'address' => '456 Main St',
        ])->assertCreated();

        $request = ResidentRegistrationRequest::where('email', 'maria@example.com')->firstOrFail();

        $this->withSession([
            'admin_logged_in' => true,
            'admin_role' => 'admin',
            'admin_name' => 'Chairman Test',
        ])->post("/resident-registration-requests/{$request->id}/approve")
            ->assertOk()
            ->assertJsonPath('message', 'Resident registration approved.');

        $this->assertDatabaseHas('users', [
            'email' => 'maria@example.com',
            'role' => 'resident',
        ]);

        $login = $this->postJson('/resident/login', [
            'email' => 'maria@example.com',
            'password' => 'secret123',
        ]);

        $login->assertOk()
            ->assertJsonPath('user.email', 'maria@example.com')
            ->assertJsonPath('user.fullname', 'Maria Reyes Dela Cruz');

        $this->post('/resident/register', [
            'username' => 'pedro.d',
            'email' => 'pedro@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'first_name' => 'Pedro',
            'middle_name' => null,
            'last_name' => 'Dela Cruz',
            'contact' => '09123456789',
            'age' => 29,
            'address' => '789 Main St',
        ])->assertCreated();

        $declined = ResidentRegistrationRequest::where('email', 'pedro@example.com')->firstOrFail();

        $this->withSession([
            'admin_logged_in' => true,
            'admin_role' => 'admin',
            'admin_name' => 'Chairman Test',
        ])->post("/resident-registration-requests/{$declined->id}/decline", [
            'reason' => 'Incomplete documents',
        ])->assertOk()
            ->assertJsonPath('message', 'Resident registration declined.');

        $this->assertDatabaseHas('resident_registration_requests', [
            'email' => 'pedro@example.com',
            'status' => 'declined',
        ]);

        $declinedLogin = $this->postJson('/resident/login', [
            'email' => 'pedro@example.com',
            'password' => 'secret123',
        ]);

        $declinedLogin->assertStatus(403)
            ->assertJsonPath('message', 'Your registration was declined. Please contact the barangay office.');
    }
}
