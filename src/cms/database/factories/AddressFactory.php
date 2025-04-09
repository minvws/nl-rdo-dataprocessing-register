<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Processor;
use App\Models\Responsible;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var Processor|Responsible $model */
        $model = $this->faker->randomElement([
            Processor::class,
            Responsible::class,
        ]);

        return [
            'addressable_id' => $model::factory(),
            'addressable_type' => $model,

            'address' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),

            'postbox' => $this->faker->streetAddress(),
            'postbox_postal_code' => $this->faker->postcode(),
            'postbox_city' => $this->faker->city(),
            'postbox_country' => $this->faker->country(),
        ];
    }
}
