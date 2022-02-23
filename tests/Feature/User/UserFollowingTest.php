<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserFollowingTest extends TestCase
{
    public function test_user_can_get_user_followings()
    {
        $user = User::factory()->hasFollowings(5)->create();

        $response = $this->getJson(route('users.followings.index', [$user]));

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has(
                'data.0',
                fn ($json) => $json->missing('email')
                    ->etc()
            )->etc());
    }
}
