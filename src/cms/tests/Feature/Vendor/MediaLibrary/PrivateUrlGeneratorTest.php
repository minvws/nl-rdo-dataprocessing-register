<?php

declare(strict_types=1);

namespace Tests\Feature\Vendor\MediaLibrary;

use App\Vendor\MediaLibrary\Media;
use App\Vendor\MediaLibrary\PrivateUrlGenerator;
use Mockery;
use Webmozart\Assert\InvalidArgumentException;

use function config;
use function expect;
use function it;

it('can generate a private url', function (): void {
    $media = Mockery::mock(Media::class)
        ->allows('getAttribute')
        ->with('uuid')
        ->andReturns('uuid')
        ->getMock();

    $pathGenerator = $this->app->get(PrivateUrlGenerator::class);
    $pathGenerator->setMedia($media);
    $url = $pathGenerator->getUrl();

    expect($url)->toBe(config('app.url') . '/media/uuid');
});

it('throws an exception if no media is set', function (): void {
    $pathGenerator = $this->app->get(PrivateUrlGenerator::class);
    $pathGenerator->getUrl();
})->expectException(InvalidArgumentException::class);
