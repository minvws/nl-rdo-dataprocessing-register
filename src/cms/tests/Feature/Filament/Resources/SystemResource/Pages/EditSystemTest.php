<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Filament\Resources\SystemResource\Pages\EditSystem;
use App\Models\System;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $system = System::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(SystemResource::getUrl('edit', ['record' => $system->id]))
        ->assertSuccessful();
});

it('can not save a system with a non-unique description', function (): void {
    $description = fake()->uuid();

    System::factory()
        ->recycle($this->organisation)
        ->create(['description' => $description]);
    $system = System::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditSystem::class, ['record' => $system->id])
        ->fillForm([
            'description' => $description,
        ])
        ->call('save')
        ->assertHasFormErrors(['description' => 'unique']);
});
