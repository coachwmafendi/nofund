<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Payout;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    private static array $actions = [
        'donation.created',
        'donation.failed',
        'donation.refunded',
        'campaign.updated',
        'campaign.created',
        'campaign.completed',
        'campaign.paused',
        'refund.processed',
        'refund.requested',
        'refund.failed',
        'user.invited',
        'user.login',
        'user.logout',
        'payout.approved',
        'payout.paid',
        'payout.failed',
        'payout.requested',
        'donor.created',
        'donor.updated',
        'settings.updated',
    ];

    private static array $subjectTypes = [
        Campaign::class,
        Donation::class,
        Donor::class,
        User::class,
        Refund::class,
        Payout::class,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => null, // Set via relationship
            'user_id' => fake()->boolean(50) ? null : null, // Set via relationship
            'action' => fake()->randomElement(self::$actions),
            'subject_type' => fake()->randomElement(self::$subjectTypes),
            'subject_id' => fake()->uuid(),
            'properties' => [
                'browser' => fake()->userAgent(),
                'ip' => fake()->ipv4(),
                'changes' => [
                    'status' => ['from' => 'draft', 'to' => 'active'],
                ],
            ],
            'ip_address' => fake()->ipv4(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        // Activity logs are immutable - no after-creating hooks
        return $this;
    }

    /**
     * Indicate that this is a system action.
     */
    public function systemAction(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Create a donation created log.
     */
    public function donationCreated(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'donation.created',
            'subject_type' => Donation::class,
        ]);
    }

    /**
     * Create a campaign updated log.
     */
    public function campaignUpdated(): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => 'campaign.updated',
            'subject_type' => Campaign::class,
        ]);
    }
}