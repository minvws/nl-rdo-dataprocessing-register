<?php

declare(strict_types=1);

use App\Models\Address;
use App\Models\Responsible;

it('belongs to an addressable', function (): void {
    $responsible = Responsible::factory()->create();

    $address = Address::factory()
        ->for($responsible, 'addressable')
        ->create();

    expect($address->addressable->id)
        ->toBe($responsible->id)
        ->and($responsible->address->id)
        ->toBe($address->id);
});
