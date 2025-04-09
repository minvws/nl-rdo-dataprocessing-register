<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecord\Pages\EditDataBreachRecord;
use App\Filament\Resources\DataBreachRecordResource;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\DataBreachRecord;
use App\Models\Document;
use App\Models\EntityNumber;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\EntityNumberService;
use App\Services\Notification\DataBreachNotificationService;
use Filament\Forms\Components\Select;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        DataBreachRecordResource::getUrl('edit', ['record' => $dataBreachRecord->id]),
    )->assertSuccessful();
});

it('can be saved', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $name = fake()->uuid();

    livewire(EditDataBreachRecord::class, [
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
    $this->mock(DataBreachNotificationService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('sendNotifications')
            ->once();
    });

    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'ap_reported' => false,
        ]);

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->fillForm([
            'ap_reported' => true,
        ])
        ->call('save');
});

it('will not send notifications if not ap_reported or no change', function (bool $oldApReported, bool $newApReported): void {
    $this->mock(DataBreachNotificationService::class, static function (MockInterface $mock): void {
        $mock->shouldReceive('sendNotifications')
            ->never();
    });

    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create([
            'ap_reported' => $oldApReported,
        ]);

    livewire(EditDataBreachRecord::class, [
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
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();
    expect($dataBreachRecord->avgResponsibleProcessingRecords->count())
        ->toBe(0);

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->fillForm([
            'avgResponsibleProcessingRecords' => [$avgResponsibleProcessingRecord->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->avgResponsibleProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a avgResponsibleProcessingRecord', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'avgResponsibleProcessingRecords',
            static function (Select $field) use ($avgResponsibleProcessingRecord): bool {
                return $field->getSearchResults($avgResponsibleProcessingRecord->name) === [
                    $avgResponsibleProcessingRecord->id => $avgResponsibleProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a avgProcessorProcessingRecord', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();
    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(0);

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->fillForm([
            'avgProcessorProcessingRecords' => [$avgProcessorProcessingRecord->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a avgProcessorProcessingRecord', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'avgProcessorProcessingRecords',
            static function (Select $field) use ($avgProcessorProcessingRecord): bool {
                return $field->getSearchResults($avgProcessorProcessingRecord->name) === [
                    $avgProcessorProcessingRecord->id => $avgProcessorProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a WpgProcessingRecord', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();
    expect($dataBreachRecord->avgProcessorProcessingRecords->count())
        ->toBe(0);

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->fillForm([
            'wpgProcessingRecords' => [$wpgProcessingRecord->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->wpgProcessingRecords->count())
        ->toBe(1);
});

it('can do a lookup for a WpgProcessingRecord', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'wpgProcessingRecords',
            static function (Select $field) use ($wpgProcessingRecord): bool {
                return $field->getSearchResults($wpgProcessingRecord->name) === [
                    $wpgProcessingRecord->id => $wpgProcessingRecord->name,
                ];
            },
        );
});

it('can be attached to a Document', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $document = Document::factory()
        ->recycle($this->organisation)
        ->create();
    expect($dataBreachRecord->documents->count())
        ->toBe(0);

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->fillForm([
            'document_id' => [$document->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $dataBreachRecord->refresh();
    expect($dataBreachRecord->documents->count())
        ->toBe(1);
});

it('can do a lookup for a document', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->create();
    $document = Document::factory()
        ->recycle($this->organisation)
        ->create();

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->assertFormFieldExists(
            'document_id',
            static function (Select $field) use ($document): bool {
                return $field->getSearchResults($document->name) === [
                    $document->id => $document->name,
                ];
            },
        );
});

it('can be cloned', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()
        ->recycle($this->organisation)
        ->withValidState()
        ->withAllRelatedEntities()
        ->create();

    $entityNumber = EntityNumber::factory()
        ->create();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock) use ($entityNumber): void {
        $mock->expects('generate')
            ->andReturn($entityNumber);
    });

    livewire(EditDataBreachRecord::class, [
        'record' => $dataBreachRecord->getRouteKey(),
    ])
        ->callAction('clone')
        ->assertRedirect();

    $dataBreachRecordClone = DataBreachRecord::query()
        ->where('entity_number_id', $entityNumber->id)
        ->firstOrFail();

    expect($dataBreachRecordClone->entity_number_id)->not()->toBe($dataBreachRecord->entity_number_id)
        ->and($dataBreachRecordClone->name)->toBe($dataBreachRecord->name);

    expect($dataBreachRecordClone->fgRemark)->toBeNull();
    expect($dataBreachRecordClone->snapshots)->toBeEmpty();

    expect($dataBreachRecordClone->documents->pluck('id')->toArray())
        ->toBe($dataBreachRecordClone->documents->pluck('id')->toArray());

    expect($dataBreachRecordClone->responsibles->pluck('id')->toArray())
        ->toBe($dataBreachRecordClone->responsibles->pluck('id')->toArray());
});
