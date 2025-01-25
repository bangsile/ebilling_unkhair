<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billing>
 */
class BillingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trx_id' => Str::random(7),
            'no_va' => rand(1000000, 9999999),
            'nama_bank' => $this->faker->randomElement(['BRI', 'BNI', 'MANDIRI', 'BCA']),
            'nama' => fake()->name(),
            'jenis_bayar' => $this->faker->randomElement(['fee-dosen', 'ukt', 'pemkes', 'ipi']),
            'nominal' => rand(1000000, 9999999),
            'tgl_expire' => now()->addDays(2),
            'lunas' => 0,
            'detail' => json_encode([
                'email' => fake()->email(),
            ])
        ];
    }
}
