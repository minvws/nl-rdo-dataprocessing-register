<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource;
use App\Filament\Resources\ReceiverResource\Pages\CreateReceiver;
use App\Models\Receiver;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(ReceiverResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $description = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateReceiver::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Receiver::class, [
        'description' => $description,
    ]);
});
