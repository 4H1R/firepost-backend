<?php

namespace Tests\Feature\Post;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_user_posts()
    {
        $user = User::factory()->hasPosts(1)->create();
        $response = $this->getJson(route('users.posts.index', [$user]));

        $response->assertOk()->assertJsonPath('data.0.id', $user->posts->first()->id);
    }
}
