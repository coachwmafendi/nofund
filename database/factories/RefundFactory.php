<?php

namespace Database\Factories;

use App\Enums\DonationStatus;
use App\Enums\RefundStatus;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Refund>
 */
class RefundFactory extends Factory
{
    protected $model = Refund::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => null, // Set via relationship
            'donation_id' => null, // Set via relationship
            'amount' => fake()->randomFloat(2, 10, 1000),
            'currency' => 'MYR',
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement([
                RefundStatus::PENDING,
                RefundStatus::PENDING,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::SUCCEEDED,
                RefundStatus::FAILED,
            ]),
            'gateway_refund_id' => fake()->optional(0.7)->uuid(),
            'processed_by' => null, // Set via relationship
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Refund $refund) {
            // Update donation status if refunded
            if ($refund->status === RefundStatus::SUCCEEDED && $refund->donation_id) {
                $donation = Donation::find($refund->donation_id);
                if ($donation) {
                    $donation->update([
                        'status' => DonationStatus::REFUNDED,
                        'refunded_amount' => $refund->amount,
                        'refund_reason' => $refund->reason,
                    ]);
                }
            }
        });
    }

    /**
     * Indicate that the refund is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RefundStatus::PENDING,
        ]);
    }

    /**
     * Indicate that the refund is succeeded.
     */
    public function succeeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RefundStatus::SUCCEEDED,
        ]);
    }

    /**
     * Indicate that the refund is failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RefundStatus::FAILED,
        ]);
    }
}