<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AuthUserResource;
use App\Http\Resources\User\UserPageResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function show(Request $request, User $user)
    {
        $user->loadCount(['followers', 'followings', 'posts']);
        if ($request->user()) {
            $user['is_followed'] = $user->followers()->where('follower_id', $request->user()->id)->exists();
        }
        return new UserPageResource($user);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'username' => ['bail', 'required', 'string', 'max:31', 'min:5', 'regex:/^[a-zA-Z0-9._]+$/', Rule::unique('users')->ignore($user->id),],
            'bio' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'image', 'max:512'],
        ]);

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::delete($user->getRawOriginal('image'));
            }
            $validated['image'] = Storage::put("$user->id", $request->image);
        }

        $user->update($validated);
        return new AuthUserResource($user);
    }

    public function destroy(User $user)
    {
        //
    }
}
