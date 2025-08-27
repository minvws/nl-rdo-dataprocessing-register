<?php

declare(strict_types=1);

use App\ValueObjects\OneTimePassword\Secret;

it('can instantiate from a valid string', function (): void {
    $word = fake()->word();
    $code = Secret::fromString($word);

    expect($code->toString())
        ->toBe($word);
});

it('can not instantiate from an empty string', function (): void {
    Secret::fromString('');
})->throws(InvalidArgumentException::class);
