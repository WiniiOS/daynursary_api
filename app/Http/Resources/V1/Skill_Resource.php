<?php

namespace App\Http\Resources\V1;

use App\Models\SkillType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Skill_Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $type = SkillType::findOrFail($this->skill_type_id);
          return [
            'id' => $this->id,
            'name' => $this->name,
            'image'=>$this->image,
            'type'=> $type->name,
            'showError'=>false,
            'selected'=>false,
            'level'=>''
        ];
    }
}
