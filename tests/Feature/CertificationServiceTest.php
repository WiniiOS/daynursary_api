<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\ProfileCertification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Certifications\Service\CertifcationService;

class CertificationServiceTest extends TestCase
{
    protected CertifcationService $certificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->certificationService = new CertifcationService();
    }

    public function testGetCertifications()
    {
        $profilCertifications = $this->certificationService->getCertifications();

        $this->assertInstanceOf(Collection::class, $profilCertifications);
    }

    public function testGetProfilCertifications()
    {
        $profilCertifications = $this->certificationService->getProfilCertifications();

        $this->assertInstanceOf(Collection::class, $profilCertifications);
    }

    public function testCreateProfilCertifications()
    {
        $user =  User::find(6);
        $this->actingAs($user);

        $attributes = [
            'certification_id' => 1,
            'issuing_organization' => 'Example Organization',
            'issue_date' => '2023-01-01',
            'expiration_date' => '2024-01-01',
            'certificate_does_not_expire' => false,
            'issuer_id' => 2,
            'issuer_url' => 'http://example.com',
        ];

        $createdCertification = $this->certificationService->createProfilCertifications($attributes);

        $this->assertInstanceOf(ProfileCertification::class, $createdCertification);
        // Ajoutez d'autres assertions en fonction du comportement attendu de la méthode
    }


    public function testUpdateProfilCertifications()
    {
        $user =  User::find(6);
        $this->actingAs($user);

        // Créez d'abord une certification de profil pour la mettre à jour
        $attributes = [
            'certification_id' => 1,
            'issuing_organization' => 'Example Organization',
            'issue_date' => '2023-01-01',
            'expiration_date' => '2024-01-01',
            'certificate_does_not_expire' => false,
            'issuer_id' => 2,
            'issuer_url' => 'http://example.com',
        ];

        $createdCertification = $this->certificationService->createProfilCertifications($attributes);

        // Ensuite, mettez à jour la certification de profil
        $updatedAttributes = [
            'certification_id' => 1,
            'issuing_organization' => 'Example Organization',
            'issue_date' => '2023-01-01',
            'expiration_date' => '2024-01-01',
            'certificate_does_not_expire' => false,
            'issuer_id' => 2,
            'issuer_url' => 'http://example.com',
        ];

        $this->certificationService->updateProfilCertifications($updatedAttributes, $createdCertification['id']);

        // Récupérez à nouveau la certification de profil pour vérifier les mises à jour
        $updatedCertification = ProfileCertification::find($createdCertification['id']);

        // Ajoutez des assertions pour vérifier que la mise à jour a été effectuée correctement
        $this->assertEquals($updatedAttributes['issue_date'], $updatedCertification->issue_date);
        $this->assertEquals($updatedAttributes['expiration_date'], $updatedCertification->expiration_date);
    }

    public function testDeleteProfilCertifications()
    {
        $user =  User::find(6);
        $this->actingAs($user);
        // Créez d'abord une certification de profil pour la supprimer ensuite
        $attributes = [
            'certification_id' => 1,
            'issuing_organization' => 'Example Organization',
            'issue_date' => '2023-01-01',
            'expiration_date' => '2024-01-01',
            'certificate_does_not_expire' => false,
            'issuer_id' => 2,
            'issuer_url' => 'http://example.com',
        ];

        $createdCertification = $this->certificationService->createProfilCertifications($attributes);

        // Supprimez la certification de profil
        $this->certificationService->deleteProfilCertifications($createdCertification['id']);

        // Essayez de récupérer la certification de profil supprimée
        $deletedCertification = ProfileCertification::find($createdCertification['id']);

        // Ajoutez des assertions pour vérifier que la certification de profil a été supprimée
        $this->assertNull($deletedCertification);
    }
}
