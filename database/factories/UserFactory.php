<?php

namespace Database\Factories;

use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $uuid = ['495b920a-e65e-4009-8285-e6fee2f73dc6', '495b920a-e65e-4069-8285-e6fee2f73dc6', '495b920a-e65u-4099-8285-e6fee2f73dc6',
        // '495b920a-e65e-4099-8275-e6fee2f73dc6', '495b920a-e65e-4099-0285-e6fee2f73dc6', '495b920a-e65e-4099-8885-e6fee2f73dc6'];
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'uuid' => Str::uuid()->toString(),
            'status_id' => 1,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
