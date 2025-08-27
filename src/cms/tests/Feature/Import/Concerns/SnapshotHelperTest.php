<?php

declare(strict_types=1);

use App\Components\Uuid\Uuid;
use App\Config\Config;
use App\Import\Factories\Concerns\SnapshotHelper;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\States\Snapshot\Established;
use App\Services\Snapshot\SnapshotFactory;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigTestHelper;

it('creates no snapshot if no configured state found', function (): void {
    ConfigTestHelper::set('import.value_converters.snapshot_state', []);
    $id = fake()->uuid();

    $factory = new class {
        use SnapshotHelper;

        public function test(string $id): void
        {
            $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create([
                'id' => Uuid::fromString($id),
            ]);

            $this->createSnapshot($avgResponsibleProcessingRecord, 1, fake()->word(), app(SnapshotFactory::class));
        }
    };
    $factory->test($id);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::query()
        ->where(['id' => $id])
        ->firstOrFail();

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(0);
});

it('creates snapshot and sets review_at', function (): void {
    $state = fake()->word();
    $reviewAtDefaultInMonths = fake()->numberBetween(1, 9);

    ConfigTestHelper::set('import.value_converters.snapshot_state', [
        $state => Established::class,
    ]);
    $id = fake()->uuid();

    $factory = new class {
        use SnapshotHelper;

        public function test(string $id, string $state, int $reviewAtDefaultInMonths): void
        {
            $organisation = Organisation::factory()
                ->create([
                    'review_at_default_in_months' => $reviewAtDefaultInMonths,
                ]);
            $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
                ->for($organisation)
                ->create([
                    'id' => Uuid::fromString($id),
                    'review_at' => null,
                ]);

            $this->createSnapshot($avgResponsibleProcessingRecord, 1, $state, app(SnapshotFactory::class));
        }
    };
    $factory->test($id, $state, $reviewAtDefaultInMonths);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::query()
        ->where(['id' => $id])
        ->firstOrFail();

    $expectedReviewAt = CalendarDate::instance(CarbonImmutable::now(Config::string('app.display_timezone')))
        ->addMonths($reviewAtDefaultInMonths);

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(1)
        ->and($avgResponsibleProcessingRecord->refresh()->review_at->equalTo($expectedReviewAt))
        ->toBeTrue();
});
