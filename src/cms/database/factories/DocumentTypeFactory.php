<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DocumentType;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentType>
 */
class DocumentTypeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),

            'name' => $this->faker->word(),
        ];
    }
}
