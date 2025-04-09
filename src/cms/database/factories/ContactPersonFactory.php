<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\ContactPerson;
use App\Models\ContactPersonPosition;
use App\Models\Organisation;
use App\Models\Wpg\WpgProcessingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactPerson>
 */
class ContactPersonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->e164PhoneNumber(),
            'import_id' => $this->faker->optional()->importId(),
            'contact_person_position_id' => ContactPersonPosition::factory(state: [
                'enabled' => true,
            ]),
        ];
    }

    public function withAddress(): self
    {
        return $this->afterCreating(static function (ContactPerson $contactPerson): void {
            Address::factory()
                ->for($contactPerson, 'addressable')
                ->create();
        });
    }

    public function withAvgProcessorProcessingRecord(): self
    {
        return $this->afterCreating(static function (ContactPerson $contactPerson): void {
            AvgProcessorProcessingRecord::factory()
                ->hasAttached($contactPerson, relationship: 'contactPersons')
                ->recycle($contactPerson->organisation)
                ->create();
        });
    }

    public function withAvgResponsibleProcessingRecord(): self
    {
        return $this->afterCreating(static function (ContactPerson $contactPerson): void {
            AvgResponsibleProcessingRecord::factory()
                ->hasAttached($contactPerson, relationship: 'contactPersons')
                ->recycle($contactPerson->organisation)
                ->create();
        });
    }

    public function withWpgProcessingRecord(): self
    {
        return $this->afterCreating(static function (ContactPerson $contactPerson): void {
            WpgProcessingRecord::factory()
                ->hasAttached($contactPerson, relationship: 'contactPersons')
                ->recycle($contactPerson->organisation)
                ->create();
        });
    }
}
