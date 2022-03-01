<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserPageResource;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = User::query()->cursorPaginate(20);
        return UserPageResource::collection($users);
    }
}
