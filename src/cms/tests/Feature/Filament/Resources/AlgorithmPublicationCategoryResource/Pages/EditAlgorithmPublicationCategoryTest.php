<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use App\Models\Algorithm\AlgorithmPublicationCategory;

it('can load the edit page', function (): void {
    $algorithmPublicationCategory = AlgorithmPublicationCategory::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmPublicationCategoryResource::getUrl('edit', ['record' => $algorithmPublicationCategory->id]))
        ->assertSuccessful();
});
