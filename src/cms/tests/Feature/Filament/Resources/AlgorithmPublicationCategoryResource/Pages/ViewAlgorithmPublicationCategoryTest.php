<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use App\Models\Algorithm\AlgorithmPublicationCategory;

it('can load the view page', function (): void {
    $algorithmPublicationCategory = AlgorithmPublicationCategory::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(AlgorithmPublicationCategoryResource::getUrl('view', ['record' => $algorithmPublicationCategory->id]))
        ->assertSuccessful();
});
