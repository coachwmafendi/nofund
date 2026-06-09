<?php

namespace Database\Factories;

use App\Enums\OrganizationStatus;
use App\Enums\OrganizationType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $malayNames = [
            'Yayasan Kebajikan Malaysia',
            'Pertubuhan Kebajikan Islam Malaysia',
            'Masjid Jamek Al-Rahman',
            'Masjid An-Nur Shah Alam',
            'Pertubuhan Budi Malaysia',
            'Yayasan Dana Kebajikan',
            'Komuniti Islam Malaysia',
            'Persatuan Kebajikan Sejahtera',
            'Yayasan Amal Malaysia',
            'Pertubuhan Muafakat Malaysia',
        ];

        $name = fake()->randomElement($malayNames) . ' ' . fake()->numberBetween(1, 99);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(5),
            'type' => fake()->randomElement(OrganizationType::cases()),
            'logo_url' => fake()->optional(0.7)->imageUrl(200, 200, 'business'),
            'description' => fake()->paragraph(3),
            'contact_email' => fake()->companyEmail(),
            'contact_phone' => fake()->optional(0.8)->phoneNumber(),
            'address' => json_encode([
                'street' => fake()->streetAddress(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'postal' => fake()->postcode(),
                'country' => 'Malaysia',
            ]),
            'timezone' => 'Asia/Kuala_Lumpur',
            'currency' => 'MYR',
            'status' => OrganizationStatus::ACTIVE,
            'plan_id' => null,
        ];
    }
}