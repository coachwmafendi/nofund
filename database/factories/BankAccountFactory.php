<?php

namespace Database\Factories;

use App\Enums\BankAccountStatus;
use App\Enums\BankAccountType;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    private static array $banks = [
        ['name' => 'Maybank', 'code' => 'MBBM'],
        ['name' => 'CIMB Bank', 'code' => 'CIMB'],
        ['name' => 'RHB Bank', 'code' => 'RHBB'],
        ['name' => 'Public Bank', 'code' => 'PBB'],
        ['name' => 'Bank Islam', 'code' => 'BIMB'],
        ['name' => 'Hong Leong Bank', 'code' => 'HLBB'],
        ['name' => 'Bank Rakyat', 'code' => 'BABB'],
        ['name' => 'Bank Muamalat', 'code' => 'BMAL'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bank = fake()->randomElement(self::$banks);

        return [
            'organization_id' => null, // Set via relationship
            'account_name' => fake()->name(),
            'account_number' => fake()->numerify('##########'),
            'bank_name' => $bank['name'],
            'bank_code' => $bank['code'],
            'type' => fake()->randomElement(BankAccountType::cases()),
            'is_default' => false, // Handle via seeder
            'status' => BankAccountStatus::ACTIVE,
        ];
    }

    /**
     * Indicate that this is the default bank account.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Indicate that this is a savings account.
     */
    public function savings(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BankAccountType::SAVINGS,
        ]);
    }

    /**
     * Indicate that this is a current account.
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BankAccountType::CURRENT,
        ]);
    }
}