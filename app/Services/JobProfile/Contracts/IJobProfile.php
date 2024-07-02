<?php

namespace App\Services\JobProfile\Contracts;

use App\Models\JobProfile;
use Illuminate\Database\Eloquent\Collection;

interface IJobProfile
{
    /**
     * Get the auth user's job profile
     *
     * @return JobProfile
     */
    public function getJobProfile(): JobProfile;

    /**
     * Get the Job Profile Languages
     *
     * @return Collection
     */
    public function getJobProfileLanguage(): Collection;

    /**
     * Get the Job profiles Work Eligibility
     *
     * @return Collection
     */
    public function retriveJobWorkEligibility(): Collection;

    /**
     * update the user's job profile
     *
     * @param JobProfile $attributes
     * @param integer $user_id
     * @return JobProfile
     */
    public function updateJobProfile(array $attributes, int $profil_id): bool;
}
