<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'bool',
        'email_verified_at' => 'datetime',
        'username_changed_at' => 'datetime',
    ];

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    // ----- Getters -----

    protected function image(): Attribute
    {
        return Attribute::make(
            get: function ($image) {
                // we are using placeholder for image for fake users
                if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                    $image = Storage::url($image);
                }
                return $image !== null ? $image : asset('assets/profile.svg');
            }
        );
    }

    // ----- Scopes -----

    public function scopeFilterByNameOrUsername($query)
    {
        $searchValue = 'q';
        if (!request()->filled($searchValue)) {
            return $query;
        }
        $q = request()->get($searchValue);
        return $query->where('name', 'like', "%$q%")->orWhere('username', 'like', "%$q%");
    }
}
