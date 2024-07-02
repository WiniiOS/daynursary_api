<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
             'center' => $this->center,
             'days' => json_decode($this->days),
             'parent' => $this->parent,
             'additionalParent' => $this->additionalParent,
             'child' => $this->child, 
             'services' => CenterServiceResource::collection($this->centerServices),
             
           
          
        ];  

        
    }


 

}

