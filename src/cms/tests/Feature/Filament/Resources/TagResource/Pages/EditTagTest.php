<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Models\Tag;

it('loads the edit page', function (): void {
    $tag = Tag::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(TagResource::getUrl('edit', ['record' => $tag->id]))
        ->assertSuccessful();
});
