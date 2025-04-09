<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\Responsible;
use App\Services\EntityNumberService;
use Carbon\CarbonImmutable;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the create page', function (): void {
    $this->get(AvgResponsibleProcessingRecordResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create an entry', function (): void {
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $name = fake()->uuid();

    livewire(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id,
            'responsible_id' => [$responsible->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(AvgResponsibleProcessingRecord::class, [
        'name' => $name,
    ]);
});

it('can use the publishFromNow action', function (): void {
    CarbonImmutable::setTestNow('2024-01-01 00:00:00');
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $name = fake()->uuid();

    livewire(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id,
            'responsible_id' => [$responsible->id],
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
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create([
            'enabled' => true,
        ]);
    /** @var Responsible $responsible */
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();
    $name = fake()->uuid();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock): void {
        $mock->expects('generate')
            ->andThrow(new InvalidArgumentException());
    });

    livewire(CreateAvgResponsibleProcessingRecord::class)
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
    livewire(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'has_security' => false,
        ])
        ->call('create')
        ->assertFormFieldIsHidden('measures_description');
});

it('does show the measures dectiption is has_security is true and other measures selected', function (): void {
    livewire(CreateAvgResponsibleProcessingRecord::class)
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

    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($this->organisation)
        ->create(['enabled' => true]);
    $responsible = Responsible::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => $name,
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id,
            'responsible_id' => [$responsible->id],
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
    livewire(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'outside_eu' => true,
            'outside_eu_protection_level' => false,
        ])
        ->call('create')
        ->assertHasFormErrors(['outside_eu_protection_level_description' => 'required']);
});
