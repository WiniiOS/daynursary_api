<?php

namespace App\Providers;

use App\Services\JobService;
use App\Services\CenterService;
use App\Services\SearchService;
use App\Services\ProfileService;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use App\Services\JobProfile\Contracts\IJobProfile;
use App\Services\JobProfile\Service\JobProfileService;
use App\Services\Certifications\Contracts\ICertifications;
use App\Services\Certifications\Service\CertifcationService;

class AppServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IJobProfile::class, JobProfileService::class);
        $this->app->singleton(ICertifications::class, CertifcationService::class);
        
        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService();
        });


        $this->app->singleton(CenterService::class, function ($app) {
            return new CenterService();
        });


        $this->app->singleton(JobService::class, function ($app) {
            return new JobService();
        });


        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService();
        });

        $this->app->singleton(ConversationService::class, function ($app) {
            return new ConversationService();
        });



        
    }

    /**
     * Bootstrap or JsonFormatResponse services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data, int $status = HttpResponse::HTTP_OK): JsonResponse {
            $success = 'success';
            $response = (empty($data)) ? ['status' => $success] : ['status' => $success, 'data' => $data];

            return response()->json($response, $status, [
                'Content-Type' => 'application/json'
            ]);
        });

        Response::macro('error', function (string $reason, int $status = HttpResponse::HTTP_BAD_REQUEST, array $extra = []): JsonResponse {
            $data = ['status' => 'error', 'reason' => $reason];
            $response = $data + $extra;

            return response()->json(
                $response,
                $status,
                [
                    'Content-Type' => 'application/json',
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );
        });
    }
}
