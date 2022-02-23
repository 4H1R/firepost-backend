<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserFollowingController extends Controller
{
    public function index(User $user)
    {
        $followings = $user->followings()->cursorPaginate(20);
        return  UserResource::collection($followings);
    }
}
