<?php

use App\Http\Controllers\Api as Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WorkExperienceController;
use App\Http\Controllers\Api\V1\ProfilePreferenceController;
use App\Http\Controllers\Api\V1\Job\JobUserProfileController;
use App\Http\Controllers\Api\V1\JobProfileDocumentController;
use App\Http\Controllers\Api\V1\EducationQualificationController;
use App\Http\Controllers\Api\V1\JobProfileImmunisationController;
use App\Http\Controllers\Api\V1\Certifications\CertificationController;

Route::middleware('auth:api')->group(function () {

    //work experience routes
    Route::post('add/work/experience', [WorkExperienceController::class, 'createWorkEperience'])->name('create_work_experience');
    Route::put('edit/work/experience/{id}', [WorkExperienceController::class, 'updateWorkEperience'])->name('update_work_experience');
    Route::get('job/type/{id}', [WorkExperienceController::class, 'job_types'])->name('job_type');
    Route::get('job/type', [WorkExperienceController::class, 'job_types'])->name('job_types');
    Route::get('job/role/{id}', [WorkExperienceController::class, 'job_roles'])->name('job_role');
    Route::get('job/role', [WorkExperienceController::class, 'job_roles'])->name('job_roles');
    Route::get('work/experience/{id}', [WorkExperienceController::class, 'work_experience'])->name('work_experience');
    Route::get('work/experience', [WorkExperienceController::class, 'work_experience'])->name('work_experiences');
    Route::delete('delete/work/experience/{id}', [WorkExperienceController::class, 'delete_work_experience'])->name('delete_work_experiences');
    Route::delete('delete/job/role/{id}', [WorkExperienceController::class, 'delete_job_role'])->name('delete_job_role');
    Route::delete('delete/job/type/{id}', [WorkExperienceController::class, 'delete_job_type'])->name('delete_job_type');

    // Education and qualification routes
    Route::post('add/education/qualification', [EducationQualificationController::class, 'createEducation'])->name('create_education_qualification');
    Route::put('edit/education/qualification/{id}', [EducationQualificationController::class, 'updateEducation'])->name('update_education_qualification');
    Route::get('education/qualification', [EducationQualificationController::class, 'profile_education'])->name('education_qualifications');
    Route::get('education/qualification/{id}', [EducationQualificationController::class, 'profile_education'])->name('education_qualification');
    Route::delete('delete/education/qualification/{id}', [EducationQualificationController::class, 'delete_education'])->name('delete_education_qualification');

    //Job profile immunisation routes
    Route::post('add/profile/immunisation', [JobProfileImmunisationController::class, 'createProfileImmunisation'])->name('create_profile_immunisation');
    Route::put('edit/profile/immunisation/{id}', [JobProfileImmunisationController::class, 'updateProfileImmunisation'])->name('update_profile_immunisation');
    Route::get('profile/immunisation', [JobProfileImmunisationController::class, 'profile_immunisation'])->name('profile_immunisations');
    Route::get('profile/immunisation/{id}', [JobProfileImmunisationController::class, 'profile_immunisation'])->name('profile_immunisation');
    Route::delete('delete/profile/immunisation/{id}', [JobProfileImmunisationController::class, 'delete_immunisation'])->name('delete_profile_immunisation');

    //get all job profile data routes
    Route::get('profile/home', [WorkExperienceController::class, 'job_profile_data'])->name('job_profile_data');


    //Job profile document routes
    Route::post('add/profile/document', [JobProfileDocumentController::class, 'createDocument'])->name('create_profile_document');
    Route::post('edit/profile/document/{id}', [JobProfileDocumentController::class, 'updateDocument'])->name('update_profile_document');
    Route::get('profile/document', [JobProfileDocumentController::class, 'profile_document'])->name('profile_documents');
    Route::get('profile/document/{id}', [JobProfileDocumentController::class, 'profile_document'])->name('profile_document');
    Route::delete('delete/profile/document/{id}', [JobProfileDocumentController::class, 'delete_document'])->name('delete_document');
    Route::post('update/profile/documents', [JobProfileDocumentController::class, 'manage_document_updates'])->name('update_profile_documents');
    Route::post('upload/jobprofile/images' , [JobProfileDocumentController::class, 'uploadJobProfileImages'])->name('upload_jobprofile_images');


    //Job profile preference routes
    Route::post('add/profile/preference', [ProfilePreferenceController::class, 'createPreference'])->name('create_profile_preference');
    Route::put('edit/profile/preference/{id}', [ProfilePreferenceController::class, 'updatePreference'])->name('update_profile_preference');
    Route::get('profile/preference', [ProfilePreferenceController::class, 'profile_preference'])->name('profile_preferences');
    Route::get('profile/preference/{id}', [ProfilePreferenceController::class, 'profile_preference'])->name('profile_preference');
    Route::delete('delete/profile/preference/{id}', [ProfilePreferenceController::class, 'delete_preference'])->name('delete_preference');

    // Job User Profile
    Route::controller(JobUserProfileController::class)->group(function () {
        Route::get('get/job/profile', 'getProfile');
        Route::get('get/job/language', 'getLanguage');
        Route::get('get/job/work_eligibility', 'getWorkEligibility');
        Route::put('update/job/profil/{user_id}', 'updateProfile');
        Route::post('upload/{user_id}', 'uploadLogo');
    });

    // Certifications
    Route::controller(CertificationController::class)->group(function () {
        Route::get('get/certif', 'get_Certifications');
        Route::get('get/profil/certif', 'getProfil_Certifications');
        Route::post('create/profil/certif', 'createProfil_Certification');
        Route::delete('delete/profil/{certif_id}', 'deleteProfil_Certification');
    });
});
