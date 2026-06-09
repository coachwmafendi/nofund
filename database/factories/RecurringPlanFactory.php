<?php

namespace Database\Factories;

use App\Enums\RecurringFrequency;
use App\Enums\RecurringPlanStatus;
use App\Models\Campaign;
use App\Models\Donor;
use App\Models\RecurringPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringPlan>
 */
class RecurringPlanFactory extends Factory
{
    protected $model = RecurringPlan::class;

    private static array $amounts = [30, 50, 50, 100, 100, 100, 200, 500];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $frequency = fake()->randomElement([
            RecurringFrequency::WEEKLY,
            RecurringFrequency::WEEKLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::MONTHLY,
            RecurringFrequency::YEARLY,
            RecurringFrequency::YEARLY,
        ]);
        $startDate = fake()->dateTimeBetween('-12 months', '-1 month');
        $now = new \DateTime();

        // Calculate total charges based on start_date and frequency
        $totalCharges = match ($frequency) {
            RecurringFrequency::WEEKLY => (int) floor($startDate->diff($now)->days / 7),
            RecurringFrequency::MONTHLY => (int) floor($startDate->diff($now)->days / 30),
            RecurringFrequency::YEARLY => (int) floor($startDate->diff($now)->days / 365),
        };
        $totalCharges = max(1, min($totalCharges, 24)); // Between 1 and 24 charges

        $amount = fake()->randomElement(self::$amounts);

        // Calculate next charge date
        $nextChargeDate = (clone $startDate);
        for ($i = 0; $i < $totalCharges; $i++) {
            $nextChargeDate = match ($frequency) {
                RecurringFrequency::WEEKLY => (clone $nextChargeDate)->modify('+1 week'),
                RecurringFrequency::MONTHLY => (clone $nextChargeDate)->modify('+1 month'),
                RecurringFrequency::YEARLY => (clone $nextChargeDate)->modify('+1 year'),
            };
        }

        return [
            'organization_id' => null,
            'donor_id' => null,
            'campaign_id' => fake()->boolean(70) ? null : null, // Set via relationship
            'amount' => $amount,
            'currency' => 'MYR',
            'frequency' => $frequency,
            'status' => fake()->randomElement([
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::ACTIVE,
                RecurringPlanStatus::PAUSED,
                RecurringPlanStatus::CANCELLED,
                RecurringPlanStatus::EXPIRED,
            ]),
            'start_date' => $startDate,
            'end_date' => fake()->optional(0.3)->dateTimeBetween('now', '+6 months'),
            'next_charge_date' => $nextChargeDate,
            'total_charges' => $totalCharges,
            'total_amount' => $totalCharges * $amount,
            'payment_method_token' => 'tok_' . fake()->regexify('[a-z0-9]{24}'),
            'gateway_subscription_id' => 'sub_' . fake()->uuid(),
            'meta' => null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (RecurringPlan $plan) {
            // Link to campaign if not set
            if (!$plan->campaign_id && $plan->organization_id) {
                $campaign = Campaign::where('organization_id', $plan->organization_id)
                    ->inRandomOrder()
                    ->first();
                if ($campaign) {
                    $plan->update(['campaign_id' => $campaign->id]);
                }
            }
        });
    }

    /**
     * Indicate that the recurring plan is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RecurringPlanStatus::ACTIVE,
        ]);
    }

    /**
     * Indicate that the recurring plan is paused.
     */
    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RecurringPlanStatus::PAUSED,
        ]);
    }

    /**
     * Indicate that the recurring plan is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RecurringPlanStatus::CANCELLED,
        ]);
    }

    /**
     * Indicate that the recurring plan is monthly.
     */
    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => RecurringFrequency::MONTHLY,
        ]);
    }
}