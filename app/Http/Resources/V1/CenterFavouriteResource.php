<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterFavouriteResource extends JsonResource
{
    public function toArray($request)
    {
          return [

            'id' => $this->id,
            'center_id'=>$this->center_id,
            'user_id'=>$this->user_id,
            'created_at' => $this->created_at,
             'user' => $this->user,
            'center' => new CenterResource($this->center)
           
            //->load('center')
        ];  
    }
}
