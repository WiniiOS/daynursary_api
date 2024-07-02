<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'logo' => $this->logo,
            'cover'=>$this->cover,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'pronoun' => $this->pronoun,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'post_code' => $this->post_code,
            'work_eligibility' => $this->work_eligibility,
            'languages' => $this->languages,
            'workExperiences' =>WorkExperienceResource::collection($this->whenLoaded('workExperience'))
        ];
    }
}

