<?php

namespace Database\Factories;

use App\Models\ApiKey;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ApiKey>
 */
class ApiKeyFactory extends Factory
{
    protected $model = ApiKey::class;

    private static array $scopes = [
        'donations:read',
        'donations:write',
        'campaigns:read',
        'campaigns:write',
        'donors:read',
        'donors:write',
        'reports:read',
        'refunds:read',
        'refunds:write',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scopeCount = fake()->numberBetween(1, 4);
        $selectedScopes = fake()->randomElements(self::$scopes, $scopeCount);

        return [
            'organization_id' => null, // Set via relationship
            'name' => fake()->words(2, true) . ' API Key',
            'key_hash' => bcrypt(Str::random(32)),
            'last_used_at' => fake()->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            'scopes' => $selectedScopes,
            'revoked_at' => null,
        ];
    }

    /**
     * Indicate that the API key has been revoked.
     */
    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'revoked_at' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the API key was recently used.
     */
    public function recentlyUsed(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_used_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the API key has all scopes.
     */
    public function fullAccess(): static
    {
        return $this->state(fn (array $attributes) => [
            'scopes' => self::$scopes,
        ]);
    }
}