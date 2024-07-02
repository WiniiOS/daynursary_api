<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterServiceResource extends JsonResource
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
            'status' => $this->status,
            'service' => $this->service,
            'price' => $this->price_per_day,
            'age_group' => $this->age_group
        ];



     

       
        
    }
}
