<?php

namespace App\Http\Resources;

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
        $data = [];
        if(isset($this->token)){
            $data['token'] = $this->token;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
           'email' => $this->email,
            'phone' => $this->telephone,
            'is_verified'=>$this->email_verified_at ? true :false,
            'image'=>$this->profile->avatar_img,
            // 'profile' => new ProfileResource($this->profile)
        ]+$data;
    }
}
