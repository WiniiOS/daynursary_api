<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class JobFavouriteResource extends JsonResource
{

    public function toArray($request)
    {
          return [

            'id' => $this->id,
            'job_id'=>$this->job_id,
            'jobprofile_id'=>$this->jobprofile_id,
            'created_at' => $this->created_at,
             'profile' => $this->JobProfile,
             'job' => new JobResource($this->Job->load('center'))
           
          
        ];  
    }
}
