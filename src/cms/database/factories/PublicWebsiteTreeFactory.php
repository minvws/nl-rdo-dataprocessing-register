<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Config\Config;
use App\Enums\Media\MediaGroup;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Webmozart\Assert\Assert;

use function assert;
use function database_path;
use function file_exists;
use function file_get_contents;
use function is_string;
use function sprintf;

/**
 * @extends Factory<PublicWebsiteTree>
 */
class PublicWebsiteTreeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'title' => $this->faker->word(),
            'slug' => $this->faker->unique()->slug(),
            'public_from' => $this->faker->optional()->date(),
            'public_website_content' => $this->faker->paragraph(),
        ];
    }

    public function withPosterImage(?Filesystem $filesystem = null): self
    {
        if ($filesystem === null) {
            $filesystem = Storage::disk(Config::string('media-library.filesystem_disk'));
        }

        return $this->afterCreating(static function (PublicWebsiteTree $publicWebsiteTree) use ($filesystem): void {
            $imagePath = database_path(sprintf('fixtures/%s_public_website_tree_poster.jpeg', $publicWebsiteTree->slug));

            if (!file_exists($imagePath)) {
                $imagePath = database_path('fixtures/public_website_tree_poster.jpeg');
            }
            Assert::fileExists($imagePath);

            $organisation = $publicWebsiteTree->organisation;
            if ($organisation === null) {
                $organisation = Organisation::factory()->create();
            }

            $posterImage = Media::factory()
                ->recycle($organisation)
                ->for($organisation)
                ->create([
                    'model_id' => $publicWebsiteTree->id,
                    'model_type' => Organisation::class,
                    'file_name' => 'poster.jpeg',
                    'mime_type' => 'image/jpeg',
                    'collection_name' => MediaGroup::PUBLIC_WEBSITE_TREE->value,
                ]);

            $imageContents = file_get_contents($imagePath);
            assert(is_string($imageContents));
            $storagePath = $posterImage->getPathRelativeToRoot();

            $filesystem->put($storagePath, $imageContents);
            $publicWebsiteTree->media()->save($posterImage);
        });
    }
}
