<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\VoucherStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'recipient_email' => $this->faker->safeEmail(),
            'value' => $this->faker->numberBetween(100, 5000),
            'currency' => $this->faker->currencyCode(),
            'status' => VoucherStatus::Issued,
            'code' => strtoupper(Str::random(16)),
        ];
    }

    public function redeemed(): static
    {
        return $this->state(['status' => VoucherStatus::Redeemed]);
    }
}
