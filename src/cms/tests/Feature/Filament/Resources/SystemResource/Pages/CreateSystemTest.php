<?php

declare(strict_types=1);

use App\Filament\Resources\SystemResource;
use App\Filament\Resources\SystemResource\Pages\CreateSystem;
use App\Models\System;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(SystemResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $description = fake()->uuid();

    livewire(CreateSystem::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(System::class, [
        'description' => $description,
    ]);
});

it('can not create an entry with an existing description', function (): void {
    $description = fake()->uuid();

    System::factory()->create(['description' => $description]);

    livewire(CreateSystem::class)
        ->fillForm([
            'description' => $description,
        ])
        ->call('create')
        ->assertHasFormErrors(['description' => 'unique']);

    $this->assertDatabaseHas(System::class, [
        'description' => $description,
    ]);
});
