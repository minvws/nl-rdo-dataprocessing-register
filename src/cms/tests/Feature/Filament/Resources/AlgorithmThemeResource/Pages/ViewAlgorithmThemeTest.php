<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmThemeResource;
use App\Models\Algorithm\AlgorithmTheme;
use Tests\Helpers\Model\OrganisationTestHelper;

it('can load the view page', function (): void {
    $organisation = OrganisationTestHelper::create();
    $algorithmTheme = AlgorithmTheme::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(AlgorithmThemeResource::getUrl('view', [
            'record' => $algorithmTheme,
        ]))
        ->assertSuccessful();
});
