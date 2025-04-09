<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Casts;

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use App\Models\Address;

use function expect;
use function it;

it('can cast a value from the database', function (): void {
    $address = Address::factory()->create();

    expect($address->id)
        ->toBeInstanceOf(UuidInterface::class);
});

it('can cast a value to the database', function (): void {
    $uuid = Uuid::generate();

    $address = Address::factory()->make([
        'id' => $uuid,
    ]);
    $address->save();

    $this->assertDatabaseHas(Address::class, [
        'id' => $address->id->toString(),
    ]);
});
