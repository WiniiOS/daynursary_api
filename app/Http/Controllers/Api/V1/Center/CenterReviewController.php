<?php

namespace App\Http\Controllers\Api\V1\Center; 

use Illuminate\Http\Request;
use App\Models\Center;
use App\Services\CenterService;


namespace App\Http\Controllers\Api\V1\Center; 

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
// use App\Traits\Transformer; 


class CenterReviewController extends Controller
{

    public function get()
    {
        $response = $this->centerService->getReview();
        return  $response;
    }

    public function get_reviews()
    {
        $response = $this->centerService->getReviews();
        return  $response;
    }

    public function add_reviews(Request $request)
    {
        $data = [];

        $response = $this->centerService->addReview($data);
        return  $response;
    }


}
