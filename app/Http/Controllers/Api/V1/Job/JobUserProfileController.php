<?php

namespace App\Http\Controllers\Api\V1\Job;

use App\Models\JobProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response as HTTP_STATUS;
use App\Services\JobProfile\Contracts\IJobProfile;

class JobUserProfileController extends Controller
{
    /** @var IJobProfile */
    protected IJobProfile $jobProfile;

    public function __construct(IJobProfile $jobProfile)
    {
        $this->jobProfile = $jobProfile;
    }

    public function getProfile(): JsonResponse
    {
        $response = $this->jobProfile->getJobProfile();

        return Response::success($response, HTTP_STATUS::HTTP_OK);
    }


    public function updateProfile(Request $request, $profil_id)
    {
        $validator = Validator::make($request->input(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'dob' => 'required',
            'phone_number' => 'required|numeric',
            'address' => 'required|string',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'post_code' => 'required|string',
            'languages' => 'required|string',
            'country_id' => 'required|integer',
            'work_eligibility' => 'required|string',
            'pronoun' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::error($validator->errors());
        }

        $array_data_filter = array_filter($request->input());
        $this->jobProfile->updateJobProfile($array_data_filter, $profil_id);

        return Response::success("Profil updated succefully");
    }

    public function getLanguage(): JsonResponse
    {
        $language = $this->jobProfile->getJobProfileLanguage();

        return Response::success($language);
    }

    public function getWorkEligibility(): JsonResponse
    {
        $workEligibility = $this->jobProfile->retriveJobWorkEligibility();

        return Response::success($workEligibility);
    }
}
