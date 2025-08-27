<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\DataBreachRecord;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Organisation;
use App\Models\Wpg\WpgProcessingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'document_type_id' => DocumentType::factory(),

            'name' => $this->faker->word(),
            'expires_at' => $this->faker->optional()->date(),
            'notify_at' => $this->faker->optional()->date(),
            'location' => $this->faker->text(),
        ];
    }

    public function withAlgorithmRecords(?int $count = null): self
    {
        return $this->afterCreating(function (Document $document) use ($count): void {
            AlgorithmRecord::factory()
                ->hasAttached($document)
                ->recycle($document->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }

    public function withAvgProcessorProcessingRecords(?int $count = null): self
    {
        return $this->afterCreating(function (Document $document) use ($count): void {
            AvgProcessorProcessingRecord::factory()
                ->hasAttached($document)
                ->recycle($document->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }

    public function withAvgResponsibleProcessingRecords(?int $count = null): self
    {
        return $this->afterCreating(function (Document $document) use ($count): void {
            AvgResponsibleProcessingRecord::factory()
                ->hasAttached($document)
                ->recycle($document->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }

    public function withDataBreachRecords(?int $count = null): self
    {
        return $this->afterCreating(function (Document $document) use ($count): void {
            DataBreachRecord::factory()
                ->hasAttached($document)
                ->recycle($document->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }

    public function withWpgProcessingRecords(?int $count = null): self
    {
        return $this->afterCreating(function (Document $document) use ($count): void {
            WpgProcessingRecord::factory()
                ->hasAttached($document)
                ->recycle($document->organisation)
                ->count($count ?? $this->faker->randomDigitNotNull())
                ->create();
        });
    }
}
