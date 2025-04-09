<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Config\Config;
use App\Enums\Media\MediaGroup;
use App\Vendor\MediaLibrary\Media;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasDefaultMediaCollections
{
    /** @use InteractsWithMedia<Media> */
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        foreach (MediaGroup::cases() as $mediaType) {
            $this->addMediaCollection($mediaType->value)
                ->useDisk(Config::string('media-library.filesystem_disk'));
        }
    }
}
