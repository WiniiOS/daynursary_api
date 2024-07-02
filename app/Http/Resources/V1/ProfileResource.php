<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'image' =>  env("AWS_URL").'/'.$this->image,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'address' => $this->address,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'centrelink' => $this->centrelink,
            'parent_profile_id' => $this->parent_profile_id,
            'user_id' => $this->user_id,
            'parent' => new ProfileResource($this->whenLoaded('parent')),
            'subProfiles' => ProfileResource::collection($this->whenLoaded('subProfiles')),
            'children' => ParentChildResource::collection($this->whenLoaded('children')),
        ];



     

       
        
    }
}
