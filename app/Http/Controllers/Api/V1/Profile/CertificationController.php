<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Controllers\Controller;
use App\Services\CertificationService;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    protected $certificationService;

    public function __construct(CertificationService $certificationService)
    {
        $this->certificationService = $certificationService;
    }

    public function getCertifications()
    {
        $certifications = $this->certificationService->getAllCertifications();
        return response()->json($certifications);
    }

    public function createdCertifications(Request $request)
    {
        $this->validate($request, [
            // Validation rules for the incoming request data
            // Add your validation rules here
        ]);

        $certification = $this->certificationService->createCertification($request->all());
        return response()->json($certification, 201);
    }

    public function updateCertification(Request $request, $id)
    {
        // Validate the request here if needed

        // Log the request data to check if it's correctly received
        logger()->info('Request Data:', $request->all());
        info($request->all());

        $profileCertification = $this->certificationService->updateCertification($id, $request->all());

        if ($profileCertification) {
            return response()->json($profileCertification);
        } else {
            return response()->json(['message' => 'Certification not found'], 404);
        }
    }


    public function deleteCertification($id)
    {
        $deleted = $this->certificationService->deleteCertification($id);

        if ($deleted) {
            return response()->json(null, 204);
        } else {
            return response()->json(['message' => 'Certification not found'], 404);
        }
    }

    public function getDropdownData()
    {
        $dropdownData = $this->certificationService->getDropdownData();
        return response()->json($dropdownData);
    }
}
