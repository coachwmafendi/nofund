<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar_url' => fake()->optional(0.7)->imageUrl(200, 200, 'people'),
            'role' => fake()->randomElement([
                UserRole::VIEWER,
                UserRole::VIEWER,
                UserRole::VIEWER,
                UserRole::VIEWER,
                UserRole::VIEWER,
                UserRole::VIEWER,
                UserRole::MANAGER,
                UserRole::MANAGER,
                UserRole::MANAGER,
                UserRole::ADMIN,
                UserRole::SUPER_ADMIN,
            ]),
            'status' => UserStatus::ACTIVE,
            'organization_id' => null, // Set via relationship
            'last_login_at' => fake()->optional(0.7) ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a super admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::SUPER_ADMIN,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::ADMIN,
        ]);
    }

    /**
     * Indicate that the user is a manager.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::MANAGER,
        ]);
    }

    /**
     * Indicate that the user is a viewer.
     */
    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::VIEWER,
        ]);
    }

    /**
     * Indicate that the user is invited.
     */
    public function invited(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::INVITED,
        ]);
    }

    /**
     * Indicate that the user is deactivated.
     */
    public function deactivated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::DEACTIVATED,
        ]);
    }

    /**
     * Create a user for a specific organization.
     */
    public function forOrganization($organization): static
    {
        return $this->state(fn (array $attributes) => [
            'organization_id' => $organization->id ?? $organization,
        ]);
    }
}