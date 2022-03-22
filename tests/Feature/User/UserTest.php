<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
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

    public function test_user_can_not_update_other_user_data()
    {
        Sanctum::actingAs(User::factory()->create());
        $user =  User::factory()->create();

        $response = $this->postJson(route('users.update', [$user]), [
            'name' => 'new name',
            'username' => 'newusername',
            'bio' => 'new bio',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_update_his_own_data()
    {
        $user =  User::factory()->create();
        Sanctum::actingAs($user);
        $data = [
            'name' => 'new name',
            'username' => 'newusername',
            'bio' => 'new bio',
        ];

        $response = $this->postJson(route('users.update', [$user]), $data);

        $response->assertOk();
        $this->assertDatabaseHas('users', $data);
    }
}
