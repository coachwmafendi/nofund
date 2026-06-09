<?php

namespace Database\Factories;

use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WebhookDelivery>
 */
class WebhookDeliveryFactory extends Factory
{
    protected $model = WebhookDelivery::class;

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
        $responseStatus = fake()->randomElement([
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            404,
            404,
            404,
            404,
            500,
        ]);

        return [
            'webhook_id' => null, // Set via relationship
            'event' => fake()->randomElement(self::$events),
            'payload' => [
                'id' => fake()->uuid(),
                'event' => fake()->randomElement(self::$events),
                'data' => [
                    'id' => fake()->uuid(),
                    'amount' => fake()->randomFloat(2, 10, 50000),
                    'currency' => 'MYR',
                    'status' => fake()->randomElement(['succeeded', 'pending', 'failed']),
                    'created_at' => fake()->dateTimeBetween('-1 month', 'now')->format('c'),
                ],
                'timestamp' => fake()->dateTimeBetween('-1 month', 'now')->format('c'),
            ],
            'response_status' => $responseStatus,
            'response_body' => fake()->optional(0.7)->sentence(),
            'attempt_count' => fake()->numberBetween(1, 3),
            'delivered_at' => $responseStatus === 200 ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (WebhookDelivery $delivery) {
            // Link to webhook if not set
            if (!$delivery->webhook_id && $delivery->organization_id) {
                $webhook = Webhook::where('organization_id', $delivery->organization_id)->first();
                if ($webhook) {
                    $delivery->update(['webhook_id' => $webhook->id]);
                }
            }
        });
    }

    /**
     * Indicate that the delivery was successful.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'response_status' => 200,
            'delivered_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the delivery failed with 404.
     */
    public function notFound(): static
    {
        return $this->state(fn (array $attributes) => [
            'response_status' => 404,
            'response_body' => 'Not Found',
            'delivered_at' => null,
        ]);
    }

    /**
     * Indicate that the delivery failed with 500.
     */
    public function serverError(): static
    {
        return $this->state(fn (array $attributes) => [
            'response_status' => 500,
            'response_body' => 'Internal Server Error',
            'delivered_at' => null,
        ]);
    }
}