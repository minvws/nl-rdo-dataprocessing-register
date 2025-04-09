<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource;
use App\Models\Receiver;

it('can load the ViewReceiver page', function (): void {
    $receiver = Receiver::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(ReceiverResource::getUrl('view', ['record' => $receiver->id]))
        ->assertSuccessful();
});
