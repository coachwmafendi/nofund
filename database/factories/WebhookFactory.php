<?php

namespace Database\Factories;

use App\Enums\WebhookStatus;
use App\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Webhook>
 */
class WebhookFactory extends Factory
{
    protected $model = Webhook::class;

    private static array $events = [
        'donation.succeeded',
        'donation.failed',
        'donation.refunded',
        'payout.paid',
        'payout.failed',
        'campaign.completed',
        'campaign.created',
        'campaign.updated',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventCount = fake()->numberBetween(1, 5);
        $selectedEvents = fake()->randomElements(self::$events, $eventCount);

        return [
            'organization_id' => null, // Set via relationship
            'url' => fake()->url() . '/webhooks/' . Str::random(16),
            'secret' => Str::random(32),
            'events' => $selectedEvents,
            'status' => fake()->randomElement([
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::ACTIVE,
                WebhookStatus::PAUSED,
            ]),
            'last_triggered_at' => fake()->optional(0.6)->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the webhook is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebhookStatus::ACTIVE,
        ]);
    }

    /**
     * Indicate that the webhook is paused.
     */
    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebhookStatus::PAUSED,
        ]);
    }

    /**
     * Indicate that the webhook has been recently triggered.
     */
    public function recentlyTriggered(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_triggered_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}