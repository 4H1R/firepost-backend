<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AuthUserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'username' => ['required', 'string', 'min:3', 'max:31', 'unique:users'],
            'email' => ['bail', 'required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['bail', 'required', 'string', 'max:31', 'min:5', 'regex:/^[a-zA-Z0-9._]+$/', 'unique:users,username'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([...$validated, 'password' => Hash::make($validated['password'])]);

        event(new Registered($user));

        Auth::login($user);

        return (new AuthUserResource($request->user()->fresh()))
            ->response()
            ->setStatusCode(201);
    }
}
