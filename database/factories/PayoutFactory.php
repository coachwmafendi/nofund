<?php

namespace Database\Factories;

use App\Enums\PayoutStatus;
use App\Models\BankAccount;
use App\Models\Donation;
use App\Models\Payout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payout>
 */
class PayoutFactory extends Factory
{
    protected $model = Payout::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            PayoutStatus::PENDING,
            PayoutStatus::PENDING,
            PayoutStatus::PROCESSING,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::PAID,
            PayoutStatus::FAILED,
        ]);

        return [
            'organization_id' => null, // Set via relationship
            'amount' => fake()->randomFloat(2, 500, 50000),
            'currency' => 'MYR',
            'status' => $status,
            'bank_account_id' => null, // Set via relationship
            'gateway_payout_id' => fake()->optional(0.7)->uuid(),
            'donations' => [], // Set via relationship
            'failure_reason' => null,
            'paid_at' => $status === PayoutStatus::PAID ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Payout $payout) {
            // Set bank_account_id if not set
            if (!$payout->bank_account_id && $payout->organization_id) {
                $bankAccount = BankAccount::where('organization_id', $payout->organization_id)->first();
                if ($bankAccount) {
                    $payout->update(['bank_account_id' => $bankAccount->id]);
                }
            }

            // Generate random donation IDs if empty
            if (empty($payout->donations) && $payout->organization_id) {
                $donationIds = Donation::where('organization_id', $payout->organization_id)
                    ->where('status', 'succeeded')
                    ->inRandomOrder()
                    ->limit(fake()->numberBetween(1, 20))
                    ->pluck('id')
                    ->toArray();
                $payout->update(['donations' => $donationIds]);
            }
        });
    }

    /**
     * Indicate that the payout is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::PENDING,
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payout is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::PROCESSING,
            'paid_at' => null,
        ]);
    }

    /**
     * Indicate that the payout is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::PAID,
            'paid_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the payout failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PayoutStatus::FAILED,
            'failure_reason' => fake()->sentence(),
            'paid_at' => null,
        ]);
    }
}