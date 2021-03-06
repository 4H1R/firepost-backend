<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPageResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        return array_merge($data, [
            'followings_count' => $this->followings_count ?? 0,
            'followers_count' => $this->followers_count ?? 0,
            'posts_count' => $this->posts_count ?? 0,
            'is_followed' => $this->is_followed ?? false,
        ]);
    }
}
