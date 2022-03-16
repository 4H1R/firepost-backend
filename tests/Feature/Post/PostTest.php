<?php

namespace Tests\Feature\Post;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_user_can_see_posts_index()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson(route('posts.index'));

        $response->assertOk();
    }

    public function test_guest_user_cannot_see_posts_index()
    {
        $response = $this->getJson(route('posts.index'));

        $response->assertUnauthorized();
    }
}
