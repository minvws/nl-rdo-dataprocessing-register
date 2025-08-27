<?php

declare(strict_types=1);

use App\ValueObjects\OneTimePassword\Code;

it('can instantiate from a valid string', function (): void {
    $word = fake()->word();
    $code = Code::fromString($word);

    expect($code->toString())
        ->toBe($word);
});

it('can not instantiate from an empty string', function (): void {
    Code::fromString('');
})->throws(InvalidArgumentException::class);
