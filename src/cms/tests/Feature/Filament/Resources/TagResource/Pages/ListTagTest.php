<?php

declare(strict_types=1);

use App\Filament\Resources\TagResource\Pages\ListTags;
use App\Models\Tag;

use function Pest\Livewire\livewire;

it('loads the list page', function (): void {
    $tags = Tag::factory()
        ->recycle($this->organisation)
        ->count(5)
        ->create();

    livewire(ListTags::class)
        ->assertCanSeeTableRecords($tags);
});
