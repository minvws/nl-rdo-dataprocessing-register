<?php

declare(strict_types=1);

use App\Filament\Resources\ResponsibleLegalEntityResource;
use App\Models\ResponsibleLegalEntity;

it('can load the view page', function (): void {
    $responsibleLegalEntity = ResponsibleLegalEntity::factory()
        ->create();

    $this->asFilamentUser()
        ->get(ResponsibleLegalEntityResource::getUrl('view', [
            'record' => $responsibleLegalEntity,
        ]))
        ->assertSuccessful();
});
