<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private function pickUsers(Collection $users, int $count, int $except = 0)
    {
        $randomUsers = $users->random($count);
        if ($except > 0) {
            $randomUsers = $randomUsers->filter(fn ($user) => $user->id !== $except);
        }
        return $randomUsers->pluck('id');
    }

    public function run()
    {
        $users =  User::factory(40)->create();

        $users->each(function ($user) use ($users) {
            $user->followers()->attach($this->pickUsers($users, rand(0, 40), $user->id));
        });
    }
}
