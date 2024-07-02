<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'name' => $this->title,
            'job_type' => $this->job_type,
            'job_info' => $this->job_info,
            'service_to_render' => $this->service_to_render,
            'start_date' => $this->start_date,
            'min_pay' => $this->min_pay,
            'max_pay' => $this->max_pay,
            'pay_type' => $this->pay_type,
            'language' => $this->language,
            'eligibility' => $this->eligibility,
            'cover' => $this->cover, // Assuming you added a 'cover' column
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'center' => new CenterResource($this->center),

           // 'skills' => SkillResource::collection($this->whenLoaded('skills')),

            'certifications' => CertificationResource::collection($this->certifications),
            
            'features' => FeatureResource::collection($this->features),

            'languages' =>LanguageResource::collection($this->languages),

            'skills' =>Skill_Resource::collection($this->skills),

            'immunisations'=>ImmunisationResource::collection($this->immunisations)
        ];

    }
}
