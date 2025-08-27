<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Filament\Resources\TagResource\Pages\CreateTag;
use App\Models\Tag;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(TagResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->word();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateTag::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Tag::class, [
        'name' => $name,
    ]);
});
