<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'image' => $this->image,
            'bio' => $this->bio,
            'is_verified' => $this->is_verified,
            'is_followed' => $this->when(isset($this->is_followed), $this->is_followed),
        ];
    }
}
