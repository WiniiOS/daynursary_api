<?php

namespace App\Http\Resources\V1;

use App\Models\Skill;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray($request)
    {
        $skill = Skill::findOrFail($this->skill_id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'skill_level'=>$this->skill_level,
            'skill'=>new Skill_Resource($skill)
        ];
    }
}
