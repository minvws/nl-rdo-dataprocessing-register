<?php

declare(strict_types=1);

use App\Filament\Resources\ReceiverResource\Pages\ListReceivers;
use App\Models\Receiver;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $receivers = Receiver::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListReceivers::class)
        ->assertCanSeeTableRecords($receivers);
});
