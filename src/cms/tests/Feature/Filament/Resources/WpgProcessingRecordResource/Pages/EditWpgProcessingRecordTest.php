<?php

declare(strict_types=1);

use App\Filament\Resources\WpgProcessingRecordResource;
use App\Filament\Resources\WpgProcessingRecordResource\Pages\EditWpgProcessingRecord;
use App\Models\EntityNumber;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\EntityNumberService;
use Mockery\MockInterface;

use function Pest\Livewire\livewire;

it('loads the edit page', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create();

    $this->get(
        WpgProcessingRecordResource::getUrl('edit', ['record' => $wpgProcessingRecord->id]),
    )->assertSuccessful();
});

it('can be saved', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'has_processors' => false,
            'article_17_a' => false,
            'third_parties' => false,
        ]);
    $name = fake()->uuid();

    livewire(EditWpgProcessingRecord::class, [
        'record' => $wpgProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'name' => $name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $wpgProcessingRecord->refresh();
    expect($wpgProcessingRecord->name)
        ->toBe($name);
});

it('cannot be saved if no articles selected in wpggoal', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'has_processors' => false,
            'article_17_a' => false,
            'third_parties' => false,
        ]);

    livewire(EditWpgProcessingRecord::class, [
        'record' => $wpgProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'wpgGoals' => [
                [
                    'description' => fake()->sentence(),
                    'article_8' => false,
                    'article_9' => false,
                    'article_10_1a' => false,
                    'article_10_1b' => false,
                    'article_10_1c' => false,
                    'article_12' => false,
                    'article_13_1' => false,
                    'article_13_2' => false,
                    'article_13_3' => false,
                    'explanation' => fake()->sentence(),
                ],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors([
            'wpgGoals.0.article_8',
            'wpgGoals.0.article_9',
            'wpgGoals.0.article_10_1a',
            'wpgGoals.0.article_10_1b',
            'wpgGoals.0.article_10_1c',
            'wpgGoals.0.article_12',
            'wpgGoals.0.article_13_1',
            'wpgGoals.0.article_13_2',
            'wpgGoals.0.article_13_3',
        ]);
});

it('cant be saved if at least one article selected in wpggoal', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->create([
            'has_processors' => false,
            'article_17_a' => false,
            'third_parties' => false,
        ]);

    // set default to all false
    $articles = [
        'article_8' => false,
        'article_9' => false,
        'article_10_1a' => false,
        'article_10_1b' => false,
        'article_10_1c' => false,
        'article_12' => false,
        'article_13_1' => false,
        'article_13_2' => false,
        'article_13_3' => false,
    ];

    // and set one random element to true
    $articles[array_rand($articles)] = true;

    livewire(EditWpgProcessingRecord::class, [
        'record' => $wpgProcessingRecord->getRouteKey(),
    ])
        ->fillForm([
            'logic' => fake()->sentence(),
            'consequences' => fake()->sentence(),
            'wpgGoals' => [
                [
                    'description' => fake()->sentence(),
                    ...$articles,
                    'explanation' => fake()->sentence(),
                    'organisation_id' => $wpgProcessingRecord->organisation->id,
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can be cloned', function (): void {
    $wpgProcessingRecord = WpgProcessingRecord::factory()
        ->recycle($this->organisation)
        ->withAllRelatedEntities()
        ->create();

    $entityNumber = EntityNumber::factory()
        ->create();

    $this->mock(EntityNumberService::class, static function (MockInterface $mock) use ($entityNumber): void {
        $mock->expects('generate')
            ->andReturn($entityNumber);
    });

    livewire(EditWpgProcessingRecord::class, [
        'record' => $wpgProcessingRecord->getRouteKey(),
    ])
        ->callAction('clone')
        ->assertRedirect();

    $wpgProcessingRecordClone = WpgProcessingRecord::query()
        ->where('entity_number_id', $entityNumber->id)
        ->firstOrFail();

    expect($wpgProcessingRecordClone->entity_number_id)->not()->toBe($wpgProcessingRecord->entity_number_id)
        ->and($wpgProcessingRecordClone->name)->toBe($wpgProcessingRecord->name)
        ->and($wpgProcessingRecordClone->public_from?->toJson())->toBe($wpgProcessingRecord->public_from?->toJson());

    expect($wpgProcessingRecordClone->fgRemark)->toBeNull();
    expect($wpgProcessingRecordClone->snapshots)->toBeEmpty();

    expect($wpgProcessingRecordClone->contactPersons->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->contactPersons->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->dataBreachRecords->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->dataBreachRecords->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->documents->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->documents->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->processors->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->processors->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->remarks->pluck('body')->toArray())
        ->toBe($wpgProcessingRecord->remarks->pluck('body')->toArray());

    expect($wpgProcessingRecordClone->responsibles->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->responsibles->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->systems->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->systems->pluck('id')->toArray());

    expect($wpgProcessingRecordClone->wpgGoals->pluck('id')->toArray())
        ->toBe($wpgProcessingRecord->wpgGoals->pluck('id')->toArray());
});
