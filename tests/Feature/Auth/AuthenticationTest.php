<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertOk();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_guest_user_cannot_get_his_data()
    {
        $response = $this->getJson(route('user'));

        $response->assertUnauthorized();
    }

    public function test_auth_user_can_get_his_data()
    {
        $user =  User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson(route('user'));

        $response->assertOk()
            ->assertJsonPath('data.id', $user->id);
    }
}
