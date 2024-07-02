<?php

namespace App\Services\Certifications\Service;

use App\Models\Certification;
use App\Models\ProfileCertification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Certifications\BaseService\BaseService;
use App\Services\Certifications\Contracts\ICertifications;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CertifcationService extends BaseService implements ICertifications
{

    protected function getModelObject(string $param): Certification|ProfileCertification
    {
        if ($param === 'certif') {
            return new Certification();
        }

        return new ProfileCertification();
    }

    /**
     * @inheritDoc
     */
    public function getCertifications(): Collection
    {
        return $this->getModelObject('certif')->get();
    }

    /**
     * @inheritDoc
     */
    public function getProfilCertifications(): Collection
    {
        return $this->getModelObject('profil_certification')->with('certificate')->get();
    }

    /**
     * @inheritDoc
     */
    public function createProfilCertifications(array $attributes): ProfileCertification
    {
        $user = Auth::user();
        $attributes['job_profile_id'] = $user->jobProfile->id;
        $result = $this->getModelObject('profil_certification')->create($attributes);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function updateProfilCertifications(array $attribute, int $id)
    {
        $certification = $this->getModelObject('profil_certification')->where('id', $id)->first();

        if (!$certification) {
            throw new ModelNotFoundException('Profil certification not found');
        }

        $datas = array_filter($attribute);

        return $certification->update($datas);
    }

    /**
     * @inheritDoc
     */
    public function deleteProfilCertifications(int $id)
    {
        $certificationDeleted = $this->getModelObject('profil_certification')->where('id', $id)->first();

        if (!$certificationDeleted) {
            throw new ModelNotFoundException('Profil certification not found');
        }

        return $certificationDeleted->delete();
    }
}
