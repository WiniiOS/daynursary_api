<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileCertification>
 */
class ProfileCertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'certification_id' => 1,
            'issuing_organization' => fake()->name(),
            'issue_date' => 1,
            'expiration_date' => 21,
            'certificate_does_not_expire' => 0,
            'issuer_id' => 21,
            'issuer_url' => 21,
        ];
    }
}
