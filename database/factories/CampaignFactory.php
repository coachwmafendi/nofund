<?php

namespace Database\Factories;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    private static array $campaignTitles = [
        'Wakaf Pembinaan Masjid Baru',
        'Tabung Bantuan Banjir Malaysia',
        'Dana Pendidikan Tahfiz Al-Quran',
        'Bantuan Perpustakaan Komuniti',
        'Program Bantuan Kuota Masjid',
        'Dana Pemulihan Selepas Banjir',
        'Wakaf Tanah Perkuburan Islam',
        'Tabung Kebajikan Anak Yatim',
        'Program Sedekah Ramadhan',
        'Bantuan Perubatan Golongan Asnaf',
        'Dana Pembangunan Surau',
        'Tabung Bantuan Musibah Kebakaran',
        'Program Zakat Fitrah Malaysia',
        'Wakaf Pembinaan Sekolah Agama',
        'Bantuan Bekalan Air Desa',
        'Dana Modal usahawan Asnaf',
        'Program Qurban Setiap Tahun',
        'Bantuan Rumah Tidak Lengkap',
        'Tabung Keamanan Malaysia',
        'Dana Latihan Vokasional',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement(self::$campaignTitles) . ' ' . fake()->numberBetween(2020, 2026);
        $startDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $hasEndDate = fake()->boolean(70);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(5),
            'description' => fake()->paragraphs(3, true),
            'cover_image_url' => fake()->optional(0.7)->imageUrl(800, 400, 'nature'),
            'target_amount' => fake()->randomFloat(2, 1000, 500000),
            'raised_amount' => 0,
            'donor_count' => 0,
            'status' => fake()->randomElement([
                CampaignStatus::DRAFT,
                CampaignStatus::DRAFT,
                CampaignStatus::DRAFT,
                CampaignStatus::ACTIVE,
                CampaignStatus::ACTIVE,
                CampaignStatus::ACTIVE,
                CampaignStatus::ACTIVE,
                CampaignStatus::ACTIVE,
                CampaignStatus::PAUSED,
                CampaignStatus::COMPLETED,
            ]),
            'visibility' => fake()->randomElement([
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::PUBLIC,
                CampaignVisibility::UNLISTED,
                CampaignVisibility::UNLISTED,
                CampaignVisibility::PRIVATE,
            ]),
            'category' => fake()->randomElement([
                'Wakaf',
                'Zakat',
                'Sedekah',
                'Dana Pendidikan',
                'Bantuan Kecemasan',
                'Pembangunan',
            ]),
            'start_date' => $startDate,
            'end_date' => $hasEndDate
                ? (clone $startDate)->modify('+' . fake()->numberBetween(30, 90) . ' days')
                : null,
            'embed_code' => null,
            'meta' => fake()->optional(0.3)->randomElements([
                'featured' => fake()->boolean(),
                'priority' => fake()->numberBetween(1, 10),
            ], fake()->numberBetween(1, 2)),
            'created_by' => null,
            'organization_id' => null,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Campaign $campaign) {
            //
        })->afterCreating(function (Campaign $campaign) {
            if (!$campaign->created_by && $campaign->organization_id) {
                $user = User::where('organization_id', $campaign->organization_id)->inRandomOrder()->first();
                if ($user) {
                    $campaign->update(['created_by' => $user->id]);
                }
            }
        });
    }

    /**
     * Indicate that the campaign is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::ACTIVE,
        ]);
    }

    /**
     * Indicate that the campaign is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::COMPLETED,
        ]);
    }
}