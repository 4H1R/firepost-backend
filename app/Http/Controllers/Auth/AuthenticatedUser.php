<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AuthUserResource;
use Illuminate\Http\Request;

class AuthenticatedUser extends Controller
{
    public function __invoke(Request $request)
    {
        return new AuthUserResource($request->user());
    }
}
