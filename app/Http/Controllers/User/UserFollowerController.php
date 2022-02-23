<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserFollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index(User $user)
    {
        $followers = $user->followers()->cursorPaginate(20);
        return  UserResource::collection($followers);
    }

    public function store(Request $request, User $user)
    {
        $this->checkUserIsNotSame($user);
        $user->followers()->syncWithoutDetaching([$request->user()->id]);

        return response()->json([
            'data' => 'User followed successfully',
        ], 201);
    }

    public function destroy(Request $request, User $user)
    {
        $this->checkUserIsNotSame($user);
        $user->followers()->detach([$request->user()->id]);

        return response()->json([
            'data' => 'User unfollowed successfully',
        ], 200);
    }

    private function checkUserIsNotSame($user)
    {
        abort_if($user->id === auth()->user()->id, 403);
    }
}
