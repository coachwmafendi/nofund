<?php

namespace Database\Factories;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donor>
 */
class DonorFactory extends Factory
{
    protected $model = Donor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->boolean(15) ? 'Anonymous' : fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'is_anonymous_preference' => fake()->boolean(10),
            'total_donated' => 0,
            'donation_count' => 0,
            'first_donation_at' => null,
            'last_donation_at' => null,
            'notes' => fake()->optional(0.3)->sentence(),
            'tags' => fake()->optional(0.4)
                ? fake()->randomElements(
                    ['recurring', 'major', 'corporate', 'first-time', 'vip'],
                    fake()->numberBetween(1, 3)
                )
                : null,
            'organization_id' => null,
        ];
    }

    /**
     * Indicate that the donor prefers to remain anonymous.
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Anonymous',
            'email' => null,
            'is_anonymous_preference' => true,
        ]);
    }

    /**
     * Indicate that the donor is a recurring donor.
     */
    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'tags' => ['recurring'],
        ]);
    }

    /**
     * Indicate that the donor is a major donor.
     */
    public function major(): static
    {
        return $this->state(fn (array $attributes) => [
            'tags' => ['major', 'vip'],
        ]);
    }
}