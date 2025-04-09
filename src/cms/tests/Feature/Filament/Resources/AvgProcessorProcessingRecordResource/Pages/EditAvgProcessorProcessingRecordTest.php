<?php

declare(strict_types=1);

use App\Filament\Resources\AvgProcessorProcessingRecordResource;
use App\Filament\Resources\AvgProcessorProcessingRecordResource\Pages\EditAvgProcessorProcessingRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\EntityNumber;
use App\Services\EntityNumberService;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        AvgProcessorProcessingRecordResource::getUrl('edit', ['record' => $avgProcessorProcessingRecord->id]),
    )->assertSuccessful();
});

it('can be saved', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'has_systems' => false,
        ]);
    $name = fake()->uuid();

    livewire(EditAvgProcessorProcessingRecord::class, [
        'record' => $avgProcessorProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $avgProcessorProcessingRecord->refresh();
    expect($avgProcessorProcessingRecord->name)
        ->toBe($name);
});

it('can be cloned', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withAllRelatedEntities()
        ->create();

    $entityNumber = EntityNumber::factory()
        ->create();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock) use ($entityNumber): void {
        $mock->expects('generate')
            ->andReturn($entityNumber);
    });

    livewire(EditAvgProcessorProcessingRecord::class, [
        'record' => $avgProcessorProcessingRecord->getRouteKey(),
    ])
        ->callAction('clone')
        ->assertRedirect();

    $avgProcessorProcessingRecordClone = AvgProcessorProcessingRecord::query()
        ->where('entity_number_id', $entityNumber->id)
        ->firstOrFail();

    expect($avgProcessorProcessingRecordClone->entity_number_id)->not()->toBe($avgProcessorProcessingRecord->entity_number_id)
        ->and($avgProcessorProcessingRecordClone->name)->toBe($avgProcessorProcessingRecord->name)
        ->and($avgProcessorProcessingRecordClone->public_from?->toJson())->toBe($avgProcessorProcessingRecord->public_from?->toJson());

    expect($avgProcessorProcessingRecordClone->fgRemark)->toBeNull();
    expect($avgProcessorProcessingRecordClone->snapshots)->toBeEmpty();

    expect($avgProcessorProcessingRecordClone->avgGoals->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->avgGoals->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->contactPersons->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->contactPersons->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->dataBreachRecords->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->dataBreachRecords->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->documents->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->documents->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->processors->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->processors->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->receivers->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->receivers->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->remarks->pluck('body')->toArray())
        ->toBe($avgProcessorProcessingRecord->remarks->pluck('body')->toArray());

    expect($avgProcessorProcessingRecordClone->responsibles->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->responsibles->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->stakeholders->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->stakeholders->pluck('id')->toArray());

    expect($avgProcessorProcessingRecordClone->systems->pluck('id')->toArray())
        ->toBe($avgProcessorProcessingRecord->systems->pluck('id')->toArray());
});
