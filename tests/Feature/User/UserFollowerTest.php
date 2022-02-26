<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserFollowerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_user_followers()
    {
        $user = User::factory()->hasFollowers(5)->create();

        $response = $this->getJson(route('users.followers.index', [$user]));

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has(
                'data.0',
                fn ($json) => $json->missing('email')
                    ->etc()
            )->etc());
    }

    public function test_auth_user_can_follow_another_user()
    {
        $user = User::factory()->create();
        $authUser = User::factory()->create();
        Sanctum::actingAs($authUser);

        $response = $this->postJson(route('users.followers.store', [$user]));

        $response->assertCreated();
        $this->assertDatabaseHas('user_followers', [
            'user_id' => $user->id,
            'follower_id' => $authUser->id,
        ]);
    }

    public function test_guest_user_cannot_follow_another_user()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('users.followers.store', [$user]));

        $response->assertUnauthorized();
    }

    public function test_user_cannot_follow_himself()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('users.followers.store', [$user]));

        $response->assertForbidden();
    }

    public function test_guest_user_cannot_unfollow_another_user()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('users.followers.destroy', [$user]));

        $response->assertUnauthorized();
    }

    public function test_auth_user_can_unfollow_another_user()
    {
        $user = User::factory()->create();
        $authUser = User::factory()->create();
        $user->followers()->attach([$authUser->id]);
        Sanctum::actingAs($authUser);

        $response = $this->deleteJson(route('users.followers.store', [$user]));

        $response->assertOk();
        $this->assertDatabaseMissing('user_followers', [
            'user_id' => $user->id,
            'follower_id' => $authUser->id,
        ]);
    }

    public function test_user_cannot_unfollow_himself()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('users.followers.destroy', [$user]));

        $response->assertForbidden();
    }
}
