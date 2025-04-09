<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Filament\Resources\TagResource\Pages\CreateTag;
use App\Models\Tag;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(TagResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->word();

    livewire(CreateTag::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Tag::class, [
        'name' => $name,
    ]);
});
