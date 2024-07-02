<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\ProfileCertification;

class CertificationService
{
    protected $profileCertificationModel;
    protected $certificationModel;
    public function __construct(ProfileCertification $profileCertificationModel)
    {
        $this->profileCertificationModel = $profileCertificationModel;
    }

    public function getAllCertifications()
    {
        return $this->profileCertificationModel->with('certificate')->get();
    }

    public function createCertification(array $data)
    {
        return $this->profileCertificationModel->create($data);
    }

    public function updateCertification($id, array $data)
    {
        $profileCertification = $this->profileCertificationModel->find($id);

        if ($profileCertification) {
            $profileCertification->fill($data); // Fill the model with the new data
            $profileCertification->save(); // Save the updated model

            return $profileCertification;
        }

        return null; // Or you can throw an exception or handle as required if not found
    }



    public function deleteCertification($id)
    {
        $profileCertification = $this->profileCertificationModel->find($id);

        if ($profileCertification) {
            $profileCertification->delete();
            return true;
        }

        return false; // Or you can throw an exception or handle as required if not found
    }

    public function getAllCertificationNames()
    {
        return $this->certificationModel->pluck('name', 'id');
    }

    public function getDropdownData()
    {
        return $this->certificationModel->pluck('name', 'id');
    }
}
