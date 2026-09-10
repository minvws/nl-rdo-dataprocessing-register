<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\Responsible;
use App\Models\Tag;
use App\Services\EntityNumberService;
use Carbon\CarbonImmutable;
use Filament\Actions\Testing\TestAction;
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

it('can create an entry with a new tag', function (): void {
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
    $tagName = fake()->uuid();

    $this->assertDatabaseEmpty('taggables');

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
        ])
        ->mountAction(TestAction::make('createOption')->schemaComponent('tags'))
        ->set('mountedActions.0.data.name', $tagName)
        ->call('callMountedAction')
        ->assertHasNoFormErrors()
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
        'organisation_id' => $organisation->id,
    ]);
    $this->assertDatabaseHas(Tag::class, [
        'name' => $tagName,
        'organisation_id' => $organisation->id,
    ]);
    $this->assertDatabaseHas('taggables', [
        'taggable_type' => AvgResponsibleProcessingRecord::class,
    ]);
});

it('can create an entry with existing tags', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $tag1 = Tag::factory()
        ->recycle($organisation)
        ->create();
    $tag2 = Tag::factory()
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
            'tags' => [
                $tag1->id->toString(),
                $tag2->id->toString(),
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
    ]);
});

it('can not create an entry with tags of another organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();
    $tagSameOrganisation = Tag::factory()
        ->recycle($organisation)
        ->create();
    $tagOtherOrganisation = Tag::factory()
        ->create();
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
            'tags' => [
                $tagSameOrganisation->id->toString(),
                $tagOtherOrganisation->id->toString(),
            ],
        ])
        ->call('create')
        ->assertHasFormErrors(['tags']);

    $this->assertDatabaseEmpty(AvgResponsibleProcessingRecord::class);
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
        ->mountAction(TestAction::make('public_from_set_now')->schemaComponent('public_from'))
        ->assertActionVisible(TestAction::make('public_from_set_now')->schemaComponent('public_from'))
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
        ->mountAction(TestAction::make('createOption')->schemaComponent('avg_responsible_processing_record_service_id'))
        ->set('mountedActions.0.data.name', $avgResponsibleProcessingRecordService->name)
        ->call('callMountedAction')
        ->mountAction(TestAction::make('createOption')->schemaComponent('responsible_id'))
        ->set('mountedActions.0.data.name', $responsible->name)
        ->call('callMountedAction')
        ->set('data.name', fake()->word())
        ->call('create')
        ->assertHasNoFormErrors();
});
