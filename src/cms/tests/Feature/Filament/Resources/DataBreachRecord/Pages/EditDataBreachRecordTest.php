<?php

declare(strict_types=1);

use App\Enums\RegisterLayout;
use App\Filament\Resources\DataBreachRecord\Pages\EditDataBreachRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\DataBreachRecord;
use App\Models\Document;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\Notification\DataBreachNotificationService;
use Filament\Forms\Components\Select;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('loads the edit page with all layouts', function (RegisterLayout $registerLayout): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation, ['register_layout' => $registerLayout]);

    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentUser($user)
        ->get(DataBreachRecordResource::getUrl('edit', ['record' => $dataBreachRecord]))
        ->assertSuccessful();
})->with(RegisterLayout::cases());

it('can be saved', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $name = fake()->uuid();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->name)
        ->toBe($name);
});

it('will send notifications if ap_reported', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create([
            'ap_reported' => false,
        ]);

    $this->mock(DataBreachNotificationService::class)
        ->shouldReceive('sendNotifications')
        ->once();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'ap_reported' => true,
        ])
        ->call('save');
});

it('will not send notifications if not ap_reported or no change', function (bool $oldApReported, bool $newApReported): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create([
            'ap_reported' => $oldApReported,
        ]);

    $this->mock(DataBreachNotificationService::class)
        ->shouldReceive('sendNotifications')
        ->never();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'ap_reported' => $newApReported,
        ])
        ->call('save');
})->with([
    'old ap_reported true, new ap_reported true' => [true, true],
    'old ap_reported true, new ap_reported false' => [true, false],
    'old ap_reported false, new ap_reported false' => [false, false],
]);

it('can be attached to a avgResponsibleProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    expect($dataBreachRecord->avgResponsibleProcessingRecords->count())
        ->toBe(0);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'avgResponsibleProcessingRecords' => [$avgResponsibleProcessingRecord->id->toString()],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->avgResponsibleProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a avgResponsibleProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->assertFormFieldExists(
            'avgResponsibleProcessingRecords',
            static function (Select $field) use ($avgResponsibleProcessingRecord): bool {
                return $field->getSearchResults($avgResponsibleProcessingRecord->name) === [
                    $avgResponsibleProcessingRecord->id->toString() => $avgResponsibleProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a avgProcessorProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(0);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'avgProcessorProcessingRecords' => [$avgProcessorProcessingRecord->id->toString()],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a avgProcessorProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->assertFormFieldExists(
            'avgProcessorProcessingRecords',
            static function (Select $field) use ($avgProcessorProcessingRecord): bool {
                return $field->getSearchResults($avgProcessorProcessingRecord->name) === [
                    $avgProcessorProcessingRecord->id->toString() => $avgProcessorProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a WpgProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(0);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'wpgProcessingRecords' => [$wpgProcessingRecord->id->toString()],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->wpgProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a WpgProcessingRecord', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->assertFormFieldExists(
            'wpgProcessingRecords',
            static function (Select $field) use ($wpgProcessingRecord): bool {
                return $field->getSearchResults($wpgProcessingRecord->name) === [
                    $wpgProcessingRecord->id->toString() => $wpgProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a Document', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $document = Document::factory()
        ->recycle($organisation)
        ->create();

    expect($dataBreachRecord->documents->count())
        ->toBe(0);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->fillForm([
            'document_id' => [$document->id->toString()],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->documents->count())
        ->toBe(1);
});

it('can do a lookup for a document', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $document = Document::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->assertFormFieldExists(
            'document_id',
            static function (Select $field) use ($document): bool {
                return $field->getSearchResults($document->name) === [
                    $document->id->toString() => $document->name,
                ];
            },
        );
});

it('can be cloned', function (): void {
    $organisation = OrganisationTestHelper::create();
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->withAllRelatedEntities()
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditDataBreachRecord::class, [
            'record' => $dataBreachRecord->getRouteKey(),
        ])
        ->assertActionDoesNotExist('clone');
});
