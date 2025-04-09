<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource;
use App\Models\Receiver;

it('loads the edit page', function (): void {
    $receiver = Receiver::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ReceiverResource::getUrl('edit', ['record' => $receiver->id]))
        ->assertSuccessful();
});
