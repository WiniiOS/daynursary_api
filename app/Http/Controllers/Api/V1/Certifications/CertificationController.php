<?php

namespace App\Http\Controllers\Api\V1\Certifications;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HTTP_STATUS;
use App\Services\Certifications\Contracts\ICertifications;

class CertificationController extends Controller
{
    /** @var ICertifications $certificate */
    protected ICertifications $certificate;

    public function __construct(ICertifications $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Get all certif
     */
    public function get_Certifications(): JsonResponse
    {
        $response = $this->certificate->getCertifications();

        return Response::success($response, HTTP_STATUS::HTTP_OK);
    }

    /**
     * Get user profil certif
     */
    public function getProfil_Certifications(): JsonResponse
    {
        $response = $this->certificate->getProfilCertifications();

        return Response::success($response, HTTP_STATUS::HTTP_OK);
    }

    /**
     * Create profil
     */
    public function createProfil_Certification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'certification_id' => 'required|integer',
            'issuing_organization' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::error($validator->errors());
        }

        $response = $this->certificate->createProfilCertifications($request->input());

        return Response::success($response, HTTP_STATUS::HTTP_CREATED);
    }

    /**
     * Update profil
     */
    public function upateProfil_Certification(Request $request, $id): JsonResponse
    {
        $response = $this->certificate->updateProfilCertifications($request->input(), $id);
        $response['message'] = "Certification updated successfuly !";

        return Response::success($response, HTTP_STATUS::HTTP_OK);
    }

    /**
     * Delete profil
     */
    public function deleteProfil_Certification($id): JsonResponse
    {
        $response = $this->certificate->deleteProfilCertifications($id);
        if (!$response) {
            return Response::error("Profil certification not Deleted!", HTTP_STATUS::HTTP_NOT_IMPLEMENTED);
        }

        return Response::success("Deleted successfuly !", HTTP_STATUS::HTTP_OK);
    }
}
