<?php

namespace App\Services\Certifications\Contracts;

use App\Models\ProfileCertification;
use Illuminate\Database\Eloquent\Collection;

interface ICertifications
{
    /**
     * get all certifications
     *
     * @return Collection
     */
    public function getCertifications(): Collection;

    /**
     * Get All Profil Certifications
     *
     * @return Collection
     */
    public function getProfilCertifications(): Collection;

    /**
     * Create a certification profil
     *
     * @param array $attributes
     * @return void
     */
    public function createProfilCertifications(array $attributes): ProfileCertification;

    /**
     * Update a certification profil
     *
     * @param array $attribute
     * @param integer $id
     * @return void
     */
    public function updateProfilCertifications(array $attribute, int $id);

    /**
     * delete a user's certification
     *
     * @param integer $id
     * @return void
     */
    public function deleteProfilCertifications(int $id);
}
