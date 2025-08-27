<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Resources\AlgorithmRecordResource;

use function it;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(AlgorithmRecordResource::getUrl('create'))
        ->assertSuccessful();
});
