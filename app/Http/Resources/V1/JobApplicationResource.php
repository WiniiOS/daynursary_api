<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
             'profile' => $this->jobProfile,
             'job' => new JobResource($this->job->load('center')),
           
          
        ];  

        
    }


 

}

