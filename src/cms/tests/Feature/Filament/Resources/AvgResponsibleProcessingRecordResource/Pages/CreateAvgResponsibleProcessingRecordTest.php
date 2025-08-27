<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\Responsible;
use App\Services\EntityNumberService;
use Carbon\CarbonImmutable;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the create page with all layouts', function (RegisterLayout $registerLayout): void {
    $user = UserTestHelper::create(['register_layout' => $registerLayout]);

    $this->asFilamentUser($user)
        ->get(AvgResponsibleProcessingRecordResource::getUrl('create'))
        ->assertOk();
})->with(RegisterLayout::cases());

it('can create an entry', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
    ]);
});

it('can use the publishFromNow action', function (): void {
    CarbonImmutable::setTestNow('2024-01-01 00:00:00');
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
        ])
        ->mountFormComponentAction('public_from', 'public_from_set_now')
        ->assertFormComponentActionVisible('public_from', 'public_from_set_now')
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
        'public_from' => '2024-01-01 00:00:00',
    ]);
});

it('fails when saving the number failed and shows a notification', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $name = fake()->uuid();

    $this->mock(EntityNumberService::class)
        ->shouldReceive('generate')
        ->once()
        ->andThrow(new InvalidArgumentException());

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id,
            'responsible_id' => [$responsible->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified(__('general.number_create_failed'));

    $this->assertDatabaseMissing(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
    ]);
});

it('does not show the measures description is has_security is false', function (): void {
    $this->asFilamentUser()
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'has_security' => false,
        ])
        ->call('create')
        ->assertFormFieldIsHidden('measures_description');
});

it('does show the measures dectiption is has_security is true and other measures selected', function (): void {
    $this->asFilamentUser()
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'has_security' => true,
            'measures' => [
                'Overige beveiligingsmaatregelen',
            ],
        ])
        ->call('create')
        ->assertFormFieldIsVisible('measures_description');
});

it('can save the measures decription', function (): void {
    $measuresDescription = fake()->sentence();
    $name = fake()->word();

    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create(['enabled' => true]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
            'has_security' => true,
            'measures_description' => $measuresDescription,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertOk();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
        'measures_description' => $measuresDescription,
    ]);
});

it('requires the outside_eu_protection_level_description if outside_eu_protection_level is false', function (): void {
    $this->asFilamentUser()
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'outside_eu' => true,
            'outside_eu_protection_level' => false,
        ])
        ->call('create')
        ->assertHasFormErrors(['outside_eu_protection_level_description' => 'required']);
});

it('can create an entry using form components', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create(['enabled' => true]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->call('mountFormComponentAction', 'data.avg_responsible_processing_record_service_id', 'createOption')
        ->set('mountedFormComponentActionsData.0.name', $avgResponsibleProcessingRecordService->name)
        ->call('callMountedFormComponentAction')
        ->call('mountFormComponentAction', 'data.responsible_id', 'createOption')
        ->set('mountedFormComponentActionsData.0.name', $responsible->name)
        ->call('callMountedFormComponentAction')
        ->set('data.name', fake()->word())
        ->call('create')
        ->assertHasNoFormErrors();
});
