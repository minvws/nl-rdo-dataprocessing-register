<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Storage;
use Webmozart\Assert\Assert;

use function hash;

class MediaContentHasher
{
    public function __construct(
        private readonly string $disk,
    ) {
    }

    public function hash(Media $media): string
    {
        $filesystem = Storage::disk($this->disk);

        Assert::true(
            $filesystem->exists($media->getPathRelativeToRoot()),
            'Received a created event for a media item that doesn\'t exist in the private disk.',
        );

        $contents = $filesystem->get($media->getPathRelativeToRoot());
        Assert::string($contents, 'Could not read contents of file.');

        return hash('sha256', $contents);
    }
}
