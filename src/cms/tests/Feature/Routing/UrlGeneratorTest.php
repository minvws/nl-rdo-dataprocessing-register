<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use Tests\Helpers\ConfigHelper;

use function expect;
use function fake;
use function it;
use function sprintf;
use function url;

it('defaults to localhost if none given', function (): void {
    $previous = url()->previous();

    expect($previous)
        ->toBe(ConfigHelper::get('app.url'));
});

it('returns to session if exists', function (): void {
    $slug = fake()->slug();
    $this->get($slug);
    $previous = url()->previous();

    expect($previous)
        ->toBe(sprintf('%s/%s', ConfigHelper::get('app.url'), $slug));
});

it('returns to fallback if exists', function (): void {
    $slug = fake()->slug();
    $previous = url()->previous($slug);

    expect($previous)
        ->toBe(sprintf('%s/%s', ConfigHelper::get('app.url'), $slug));
});
