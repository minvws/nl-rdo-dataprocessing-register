<?php

declare(strict_types=1);

namespace App\Vendor\MediaLibrary;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;
use Webmozart\Assert\Assert;

use function route;

class PrivateUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        Assert::notNull($this->media, 'Tried to generate path for media without having media');

        return route('media.private', ['media' => $this->media->uuid]);
    }
}
