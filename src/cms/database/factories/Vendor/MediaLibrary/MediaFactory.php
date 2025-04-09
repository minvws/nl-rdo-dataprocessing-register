<?php

declare(strict_types=1);

namespace Database\Factories\Vendor\MediaLibrary;

use App\Config\Config;
use App\Enums\Media\MediaGroup;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Organisation;
use App\Models\Wpg\WpgProcessingRecord;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var class-string<AvgProcessorProcessingRecord>|class-string<WpgProcessingRecord> $modelToCreate */
        $modelToCreate = $this->faker->randomElement([
            AvgProcessorProcessingRecord::class,
            WpgProcessingRecord::class,
        ]);

        /** @var MediaGroup $mediaGroup */
        $mediaGroup = $this->faker->randomElement(MediaGroup::cases());

        return [
            'model_type' => $modelToCreate,
            'model_id' => $modelToCreate::factory(),
            'uuid' => $this->faker->uuid(),
            'collection_name' => $mediaGroup->value,
            'name' => $this->faker->word(),
            'file_name' => $this->faker->slug(),
            'mime_type' => $this->faker->mimeType(),
            'disk' => Config::string('media-library.filesystem_disk'),
            'conversions_disk' => Config::string('media-library.filesystem_disk'),
            'size' => $this->faker->numberBetween(1, 1000),
            'manipulations' => [],
            'custom_properties' => [],
            'responsive_images' => [],
            'generated_conversions' => [],
            'order_column' => $this->faker->numberBetween(1, 1000),
            'content_hash' => $this->faker->sha256(),

            'organisation_id' => Organisation::factory(),
            'validated_at' => $this->faker->dateTime(),
        ];
    }
}
