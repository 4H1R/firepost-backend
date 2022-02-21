<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'bool',
        'email_verified_at' => 'datetime',
        'username_changed_at' => 'datetime',
    ];

    public function getImageAttribute($image)
    {
        // we are using placeholder for image for fake users
        if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
            $image = Storage::url($image);
        }
        return $image !== null ? $image : asset('assets/profile.svg');
    }
}
