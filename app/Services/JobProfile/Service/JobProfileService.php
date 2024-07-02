<?php

namespace App\Services\JobProfile\Service;

use App\Models\Language;
use App\Models\JobProfile;
use App\Models\WorkEligibility;
use App\Exceptions\ModelException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Services\JobProfile\Contracts\IJobProfile;
use App\Services\JobProfile\BaseService\JobBaseService;

class JobProfileService extends JobBaseService implements IJobProfile
{
    protected function getModelObject(): JobProfile
    {
        return new JobProfile();
    }

    /**
     * @inheritDoc
     */
    public function getJobProfile(): JobProfile
    {
        $authUser = Auth::user();
        $jobProfil = $this->getModelObject()->where('user_id', $authUser->id)->first();

        if (!$jobProfil)
            throw new ModelException('User profil not found !');

        return $jobProfil;
    }

    /**
     * @inheritDoc
     */
    public function updateJobProfile(array $attributes, int $profil_id): bool
    {
        $jobProfil = $this->getModelObject()->where('id', $profil_id)->first();

        if (!$jobProfil)
            throw new ModelException('Profil not found !');

        return $jobProfil->update($attributes);
    }

    public function getJobProfileLanguage(): Collection
    {
        return Language::all();
    }

    public function retriveJobWorkEligibility(): Collection
    {
        return WorkEligibility::all();
    }
}
