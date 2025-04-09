<?php

declare(strict_types=1);

use App\Filament\Resources\AlgorithmRecordResource;
use App\Filament\Resources\AlgorithmRecordResource\Pages\EditAlgorithmRecord;
use App\Models\Algorithm\AlgorithmRecord;
use App\Models\EntityNumber;
use App\Services\EntityNumberService;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        AlgorithmRecordResource::getUrl('edit', ['record' => $algorithmRecord->id]),
    )->assertSuccessful();
});

it('can be saved', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->create();
    $name = fake()->word();

    livewire(EditAlgorithmRecord::class, [
        'record' => $algorithmRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $algorithmRecord->refresh();
    expect($algorithmRecord->name)
        ->toBe($name);
});

it('can be cloned', function (): void {
    $algorithmRecord = AlgorithmRecord::factory()
        ->recycle($this->organisation)
        ->withAllRelatedEntities()
        ->create();

    $entityNumber = EntityNumber::factory()
        ->create();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock) use ($entityNumber): void {
        $mock->expects('generate')
            ->andReturn($entityNumber);
    });

    livewire(EditAlgorithmRecord::class, [
        'record' => $algorithmRecord->getRouteKey(),
    ])
        ->callAction('clone')
        ->assertRedirect();

    $algorithmRecordClone = AlgorithmRecord::query()
        ->where('entity_number_id', $entityNumber->id)
        ->firstOrFail();

    expect($algorithmRecordClone->entity_number_id)->not()->toBe($algorithmRecord->entity_number_id)
        ->and($algorithmRecordClone->name)->toBe($algorithmRecord->name)
        ->and($algorithmRecordClone->public_from?->toJson())->toBe($algorithmRecord->public_from?->toJson())
        ->and($algorithmRecordClone->algorithm_theme_id)->toBe($algorithmRecord->algorithm_theme_id)
        ->and($algorithmRecordClone->algorithm_status_id)->toBe($algorithmRecord->algorithm_status_id)
        ->and($algorithmRecordClone->algorithm_publication_category_id)->toBe($algorithmRecord->algorithm_publication_category_id)
        ->and($algorithmRecordClone->algorithm_meta_schema_id)->toBe($algorithmRecord->algorithm_meta_schema_id);

    expect($algorithmRecordClone->fgRemark)->toBeNull();
    expect($algorithmRecordClone->snapshots)->toBeEmpty();

    expect($algorithmRecordClone->documents->pluck('id')->toArray())
        ->toBe($algorithmRecordClone->documents->pluck('id')->toArray());
});
