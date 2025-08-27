<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleLegalEntityResource;
use App\Models\ResponsibleLegalEntity;

it('loads the edit page', function (): void {
    $responsibleLegalEntity = ResponsibleLegalEntity::factory()
        ->create();

    $this->asFilamentUser()
        ->get(ResponsibleLegalEntityResource::getUrl('edit', [
            'record' => $responsibleLegalEntity,
        ]))
        ->assertSuccessful();
});
