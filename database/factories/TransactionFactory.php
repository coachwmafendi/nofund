<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => null, // Set via relationship
            'transactionable_type' => fake()->randomElement([
                \App\Models\Donation::class,
                \App\Models\Refund::class,
                \App\Models\Payout::class,
            ]),
            'transactionable_id' => fake()->uuid(),
            'type' => fake()->randomElement(TransactionType::cases()),
            'amount' => fake()->randomFloat(2, 10, 50000),
            'currency' => 'MYR',
            'balance_after' => fake()->randomFloat(2, 0, 100000),
            'description' => fake()->sentence(),
            'meta' => fake()->optional(0.3)->randomElements([
                'fee' => fake()->randomFloat(2, 0, 100),
                'note' => fake()->sentence(),
            ], fake()->numberBetween(1, 2)),
        ];
    }

    /**
     * Indicate that this is a donation transaction.
     */
    public function donation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::DONATION,
            'transactionable_type' => \App\Models\Donation::class,
        ]);
    }

    /**
     * Indicate that this is a refund transaction.
     */
    public function refund(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::REFUND,
            'transactionable_type' => \App\Models\Refund::class,
        ]);
    }

    /**
     * Indicate that this is a payout transaction.
     */
    public function payout(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::PAYOUT,
            'transactionable_type' => \App\Models\Payout::class,
        ]);
    }
}