<?php

declare(strict_types=1);

namespace Tests\Unit\Components\Uuid;

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use InvalidArgumentException;

use function expect;
use function fake;
use function it;

it('can create an instance with fromString', function (): void {
    $uuid = Uuid::fromString(fake()->uuid());

    expect($uuid)
        ->toBeInstanceOf(UuidInterface::class);
});

it('fails if invalid string is given to fromString', function (): void {
    $this->expectException(InvalidArgumentException::class);

    Uuid::fromString(fake()->word());
});

it('returns the correct value for __toString', function (): void {
    $uuid = fake()->uuid();
    $result = Uuid::fromString($uuid);

    expect($result->__toString())
        ->toBe($uuid);
});

it('returns the correct value for toString', function (): void {
    $uuid = fake()->uuid();
    $result = Uuid::fromString($uuid);

    expect($result->toString())
        ->toBe($uuid);
});

it('can compare two equal objects using equals', function (): void {
    $uuid = Uuid::fromString(fake()->uuid());

    expect($uuid->equals($uuid))
        ->toBeTrue();
});

it('can compare two different objects with the same value using equals', function (): void {
    $uuid = fake()->uuid();

    $first = Uuid::fromString($uuid);
    $second = Uuid::fromString($uuid);

    expect($first->equals($second))
        ->toBeTrue();
});

it('returns the correct value for livewire', function (): void {
    $uuid = fake()->uuid();

    expect(Uuid::fromString($uuid)->toLivewire())
        ->toBe(['id' => $uuid]);
});

it('returns a valid value from livewire', function (): void {
    $uuid = fake()->uuid();
    $result = Uuid::fromLivewire(['id' => $uuid]);

    expect($result->toString())
        ->toBe($uuid);
});

it('fails for a invalid value from livewire', function (): void {
    $this->expectException(InvalidArgumentException::class);

    Uuid::fromLivewire(fake()->word());
});

it('returns the correct json value', function (): void {
    $uuid = fake()->uuid();

    expect(Uuid::fromString($uuid)->jsonSerialize())
        ->toBe(['id' => $uuid]);
});
