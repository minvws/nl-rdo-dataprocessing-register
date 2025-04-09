<?php

declare(strict_types=1);

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\ContactPerson;
use App\Models\Organisation;
use App\Models\Processor;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Models\System;
use App\Models\Wpg\WpgProcessingRecord;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;
use App\Services\Snapshot\SnapshotDataFactory;
use Mockery\MockInterface;

it('creates the data for the snapshot', function (string $snapshotSourceClass): void {
    $this->mock(HugoPublicWebsiteGenerator::class, static function (MockInterface $mock): void {
        $mock->expects('generate')
            ->zeroOrMoreTimes();
    });

    $snapshotSource = $snapshotSourceClass::factory()->create();

    $snapshot = Snapshot::factory()
        ->create([
            'snapshot_source_id' => $snapshotSource->id,
            'snapshot_source_type' => $snapshotSourceClass,
        ]);

    expect($snapshot->snapshotData)
        ->toBeNull();

    /** @var SnapshotDataFactory $snapshotDataFactory */
    $snapshotDataFactory = $this->app->get(SnapshotDataFactory::class);
    $snapshotDataFactory->createDataForSnapshot($snapshot);

    $snapshot->refresh();

    expect($snapshot->snapshotData)
        ->not()->toBeNull();
})
    ->with([
        [AlgorithmRecord::class],
        [AvgProcessorProcessingRecord::class],
        [AvgProcessorProcessingRecord::class],
        [WpgProcessingRecord::class],

        [ContactPerson::class],
        [Processor::class],
        [Receiver::class],
        [Responsible::class],
        [System::class],
    ]);

it('fails with an invalid snapshotSource', function (): void {
    $this->mock(HugoPublicWebsiteGenerator::class, static function (MockInterface $mock): void {
        $mock->expects('generate')
            ->zeroOrMoreTimes();
    });

    $organisation = Organisation::factory()->create();

    $snapshot = Snapshot::factory()
        ->create([
            'snapshot_source_id' => $organisation->id,
            'snapshot_source_type' => Organisation::class,
        ]);

    /** @var SnapshotDataFactory $snapshotDataFactory */
    $snapshotDataFactory = $this->app->get(SnapshotDataFactory::class);

    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('missing snapshot-data factory for model');
    $snapshotDataFactory->createDataForSnapshot($snapshot);
});
