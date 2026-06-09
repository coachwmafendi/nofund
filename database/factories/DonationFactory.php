<?php

namespace Database\Factories;

use App\Enums\DonationStatus;
use App\Enums\PaymentMethod;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    private static array $amounts = [10, 10, 10, 20, 20, 20, 20, 20, 50, 50, 50, 100, 100, 200, 500, 1000, 2000, 5000];
    private static array $gateways = ['stripe', 'stripe', 'stripe', 'stripe', 'stripe', 'stripe', 'billplz', 'billplz', 'billplz', 'billplz', 'toyyibpay', 'toyyibpay', 'toyyibpay'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = fake()->randomElement(PaymentMethod::cases());
        $status = fake()->randomElement([
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::SUCCEEDED,
            DonationStatus::PENDING,
            DonationStatus::PENDING,
            DonationStatus::FAILED,
            DonationStatus::FAILED,
            DonationStatus::REFUNDED,
        ]);
        $isAnonymous = fake()->boolean(20);

        return [
            'organization_id' => null,
            'campaign_id' => fake()->boolean(90) ? null : null, // Set via relationship
            'donor_id' => fake()->boolean(80) ? null : null, // Set via relationship
            'amount' => fake()->randomElement(self::$amounts),
            'currency' => 'MYR',
            'status' => $status,
            'payment_method' => $paymentMethod,
            'payment_gateway' => fake()->randomElement(self::$gateways),
            'gateway_transaction_id' => fake()->uuid(),
            'gateway_response' => [
                'id' => fake()->uuid(),
                'status' => 'succeeded',
                'amount' => fake()->numberBetween(100, 50000),
                'currency' => 'myr',
            ],
            'is_anonymous' => $isAnonymous,
            'donor_name' => $isAnonymous ? 'Anonymous' : fake()->name(),
            'donor_email' => fake()->safeEmail(),
            'donor_phone' => fake()->optional(0.6)->phoneNumber(),
            'donor_message' => fake()->optional(0.4)->sentence(),
            'refunded_amount' => 0,
            'refund_reason' => null,
            'receipt_sent' => $status === DonationStatus::SUCCEEDED ? fake()->boolean(80) : false,
            'receipt_url' => fake()->optional(0.3)->url(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'source_url' => fake()->optional(0.6)->url(),
            'meta' => [
                'source' => fake()->randomElement(['website', 'mobile', 'api', 'qr']),
                'referrer' => fake()->domainWord(),
            ],
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Donation $donation) {
            // Set donor snapshot if donor_id is set
            if ($donation->donor_id && !$donation->donor_email) {
                $donor = Donor::find($donation->donor_id);
                if ($donor) {
                    $donation->donor_name = $donor->name;
                    $donation->donor_email = $donor->email;
                    $donation->donor_phone = $donation->donor_phone ?? $donor->phone;
                }
            }
        })->afterCreating(function (Donation $donation) {
            // Link to campaign if campaign_id is null but organization_id is set
            if (!$donation->campaign_id && $donation->organization_id) {
                $campaign = Campaign::where('organization_id', $donation->organization_id)
                    ->inRandomOrder()
                    ->first();
                if ($campaign) {
                    $donation->update(['campaign_id' => $campaign->id]);
                }
            }

            // Update campaign raised_amount and donor_count for succeeded donations
            if ($donation->status === DonationStatus::SUCCEEDED && $donation->campaign_id) {
                $campaign = $donation->campaign;
                if ($campaign) {
                    $campaign->increment('raised_amount', $donation->amount);
                    if (!$donation->is_anonymous) {
                        $campaign->increment('donor_count');
                    }
                }
            }

            // Update donor stats
            if ($donation->status === DonationStatus::SUCCEEDED && $donation->donor_id) {
                $donor = $donation->donor;
                if ($donor) {
                    $donor->increment('total_donated', $donation->amount);
                    $donor->increment('donation_count');
                    if (!$donor->first_donation_at) {
                        $donor->update(['first_donation_at' => $donation->created_at]);
                    }
                    $donor->update(['last_donation_at' => $donation->created_at]);
                }
            }
        });
    }

    /**
     * Indicate that the donation is succeeded.
     */
    public function succeeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DonationStatus::SUCCEEDED,
        ]);
    }

    /**
     * Indicate that the donation is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DonationStatus::PENDING,
        ]);
    }

    /**
     * Indicate that the donation is failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DonationStatus::FAILED,
        ]);
    }

    /**
     * Indicate that the donation is refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => DonationStatus::REFUNDED,
            'refunded_amount' => $attributes['amount'] ?? fake()->randomFloat(2, 10, 5000),
            'refund_reason' => fake()->sentence(),
        ]);
    }
}