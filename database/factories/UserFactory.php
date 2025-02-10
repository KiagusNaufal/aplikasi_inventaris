<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::random(20),
            'name' => 'halo',
            'password' => md5('halo'),
            'role' => fake()->randomElements(['su', 'admin', 'user']),
            'status' => 1,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function su(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'su',
        ]);
    }
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'su',
        ]);
    }
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'su',
        ]);
    }
}
