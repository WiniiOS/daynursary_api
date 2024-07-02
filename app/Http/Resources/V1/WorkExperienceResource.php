<?php

namespace App\Http\Resources\V1;

use App\Models\JobRole;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\JobProfileRoleResource;
use App\Http\Resources\V1\JobProfileTypeResource;

class WorkExperienceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = JobRole::findOrFail($this->role_id);
        $type = JobType::findOrFail($this->job_type_id);
        return [
            'id' => $this->id,
            'job_profile_id' => $this->job_profile_id,
            'role' => new JobProfileRoleResource($role),
            'company_name' => $this->company_name,
            'type' => new JobProfileTypeResource($type),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'currently_working' => $this->currently_working,
            'description' => $this->description
        ];
    }
}
