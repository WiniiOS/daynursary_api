<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return ParentChildResource::collection($this);
       
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'image' =>   $this->image? env("AWS_URL").'/'.$this->image:'',
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'dob' => $this->dob,
            'gender' => $this->gender,
            
            'centrelink' => $this->centrelink,
            'child_allergies' => $this->child_allergies,
            'special_needs' => $this->special_needs,
           
        ];

        
    }
}
