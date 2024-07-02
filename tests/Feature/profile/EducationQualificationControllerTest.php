<?php

namespace Tests\Feature\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\EducationQualificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ParentProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\JobProfile;
use App\Models\User;
use App\Models\ProfileEducation;
use Illuminate\Support\Facades\Auth;
use App\Services\EducationService;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EducationQualificationControllerTest extends TestCase
{
    // use WithFaker;

    public function testCreateProfileEducation()
    {

            $user = User::factory()->create([
                'password' => Hash::make('password'),
                'uuid' => Str::random(10),
                'status_id' => 1,
            ]);
        
        $request = new Request([
            'email' => $user->email,
            'password' => 'password',
        ]);

        //login the User
        $this->app->make('App\Http\Controllers\Api\V1\Auth\AuthController')->login($request);

        //create a new education profile
        $request = new Request([
            'qualification' => 'Test qualification',
            'start_date' => '2023-12-04',
            'end_date' => '2023-12-04',
            'currently_studying' => true,
            'description' => 'This is a test education and qualification description.',
            'field_of_study' => 'Childcare Management',
            'school' => 'Software Engineering',
        ]);

        $this->app->make('App\Http\Controllers\Api\V1\EducationQualificationController')->createEducation($request);

        $profileEducation = ProfileEducation::where('qualification', 'Test qualification')->first();
        $this->assertNotNull($profileEducation);
    }

    public function testUpdateProfileEducation()
    {
        $request = new Request([
            'qualification' => 'Updated qualification',
            'start_date' => '2023-12-04',
            'end_date' => '2023-12-04',
            'currently_studying' => true,
            'description' => 'This is a test education and qualification description.',
            'field_of_study' => 'Childcare Management',
            'school' => 'Software Engineering',
        ]);

        $profile_education = ProfileEducation::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\EducationQualificationController')->updateEducation($request, $profile_education->id);

        $this->assertJson($response->getContent());
        $profileEducation = ProfileEducation::find($profile_education->id);
        $this->assertEquals('Updated qualification', $profileEducation->qualification);
    }

    public function test_returns_all_profile_educations()
    {
        $response = $this->app->make('App\Http\Controllers\Api\V1\EducationQualificationController')->profile_education(new Request());

        $this->assertJson($response->getContent());
        $this->assertContains($response->getStatusCode(), [201, 404]);
    }

    public function test_return_profile_education_by_id()
    {
        $profileEducation = ProfileEducation::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\EducationQualificationController')->profile_education(new Request(), $profileEducation->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_delete_profile_education()
    {
        $profileEducation = ProfileEducation::factory()->new()->create();
        $response = $this->app->make('App\Http\Controllers\Api\V1\EducationQualificationController')->delete_education(new Request(), $profileEducation->id);

        $this->assertJson($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
    }

}
