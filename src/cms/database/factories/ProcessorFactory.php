<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Organisation;
use App\Models\Processor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Processor>
 */
class ProcessorFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->word(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->e164PhoneNumber(),
            'import_id' => $this->faker->optional()->importId(),
        ];
    }

    public function withAddress(): self
    {
        return $this->afterCreating(static function (Processor $processor): void {
            Address::factory()
                ->for($processor, 'addressable')
                ->create([
                    'addressable_id' => $processor->id,
                    'addressable_type' => Processor::class,
                ]);
        });
    }
}
