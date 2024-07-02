<?php

namespace Tests\Feature\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\WorkExperienceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\JobProfile;
use App\Models\JobType;
use App\Models\JobRole;
use App\Models\User;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\JobProfileService;
use Illuminate\Validation\Rule;

class WorkExperienceControllerTest extends TestCase
{
    // use WithFaker;

    public function testCreateWorkEperience()
    {

            $user = User::factory()->create([
                'password' => Hash::make('password'),
                'uuid' => Str::random(10),
                'status_id' => 1,
            ]);
         //create a new work experience
        $request = new Request([
            'email' => $user->email,
            'password' => 'password',
        ]);

        //login the User
        $this->app->make('App\Http\Controllers\Api\V1\Auth\AuthController')->login($request);

        //create a new work experience
        $jobType = JobType::factory()->new()->create();
        $jobRole = JobRole::factory()->new()->create();
        $request = new Request([
            'company_name' => 'Test Company',
            'start_date' => '2023-12-04',
            'end_date' => '2023-12-04',
            'currently_working' => true,
            'description' => 'This is a test work experience description.',
            'job_type_id' => $jobType->id,
            'role_id' => $jobRole->id,
        ]);

        $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->createWorkEperience($request);

        $workExperience = WorkExperience::where('company_name', 'Test Company')->first();
        $this->assertNotNull($workExperience);
    }

    public function testUpdateWorkEperience()
    {
        $request = new Request([
            'company_name' => 'Updated Company',
            'start_date' => '2023-12-04',
            'end_date' => '2023-12-04',
            'currently_working' => true,
            'description' => 'This is an updated work experience description.',
            'job_type_id' => 1,
            'role_id' => 1,
        ]);

        $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->updateWorkEperience($request, 1);

        $workExperience = WorkExperience::find(1);
        $this->assertEquals('Updated Company', $workExperience->company_name);
    }

    public function test_returns_all_types()
    {
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->job_types(new Request());

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_return_work_experience_by_id()
    {
        $workExperience = WorkExperience::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->work_experience(new Request(), $workExperience->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_return_all_work_experience_by_id()
    {
        $workExperience = WorkExperience::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->work_experience(new Request(), $workExperience->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_returns_all_roles()
    {
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->job_roles(new Request());

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function tests_returns_job_type_by_id()
    {
        $jobType = JobType::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->job_types(new Request(), $jobType->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_returns_role_by_id()
    {
        $jobRole = JobRole::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->job_roles(new Request(), $jobRole->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_delete_work_experience()
    {
        $workExperience = WorkExperience::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->delete_work_experience(new Request(), $workExperience->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_delete_job_type()
    {
        $jobType = JobType::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->delete_job_type(new Request(), $jobType->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_delete_job_role()
    {
    
        $jobRole = JobRole::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\WorkExperienceController')->delete_job_role(new Request(), $jobRole->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }



}

