<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource;
use App\Models\Tag;

it('loads the view page', function (): void {
    $tag = Tag::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(TagResource::getUrl('view', ['record' => $tag->id]))
        ->assertSuccessful();
});
