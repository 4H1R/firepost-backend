<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_a_user()
    {
        $user =  User::factory()->create();
        $response = $this->getJson(route('users.show', [$user]));

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
            $json->has(
                'data',
                fn ($json) => $json->where('username', $user->username)
                    ->missing('email')
                    ->etc()
            ));
    }
}
