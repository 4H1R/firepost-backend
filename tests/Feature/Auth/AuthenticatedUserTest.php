<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticatedUserTest extends TestCase
{
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
