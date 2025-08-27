<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordServiceResource;
use App\Filament\Resources\WpgProcessingRecordServiceResource\Pages\CreateWpgProcessingRecordService;
use App\Models\Wpg\WpgProcessingRecordService;

it('loads the create page', function (): void {
    $this->asFilamentUser()
        ->get(WpgProcessingRecordServiceResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $name = fake()->uuid();

    $this->asFilamentUser()
        ->createLivewireTestable(CreateWpgProcessingRecordService::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(WpgProcessingRecordService::class, [
        'name' => $name,
    ]);
});
