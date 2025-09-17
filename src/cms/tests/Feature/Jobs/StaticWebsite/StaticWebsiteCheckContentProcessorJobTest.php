<?php

declare(strict_types=1);

use App\Jobs\StaticWebsite\StaticWebsiteCheckContentProcessorJob;
use App\Models\Snapshot;
use App\Models\StaticWebsiteCheck;
use App\Models\StaticWebsiteSnapshotEntry;
use Psr\Log\NullLogger;

it('reads the content and creates a static-website snapshot-entry', function (): void {
    $snapshot = Snapshot::factory()->create();

    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [
                    [
                        'id' => $snapshot->id->toString(),
                        'permalink' => fake()->url(),
                        'type' => 'processing-record',
                    ],
                ],
            ],
        ]);

    $this->assertDatabaseCount(StaticWebsiteSnapshotEntry::class, 0);

    $staticWebsiteCheckContentProcessorJob = new StaticWebsiteCheckContentProcessorJob($staticWebsiteCheck);
    $staticWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(StaticWebsiteSnapshotEntry::class, 1);
});

it('also updates the url on an existing entry', function (): void {
    $snapshot = Snapshot::factory()->create();

    $url = fake()->url();
    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [
                    [
                        'id' => $snapshot->id->toString(),
                        'permalink' => $url,
                        'type' => 'processing-record',
                    ],
                ],
            ],
        ]);

    StaticWebsiteSnapshotEntry::factory()
        ->create([
            'last_static_website_check_id' => $staticWebsiteCheck->id,
            'snapshot_id' => $snapshot->id,
            'url' => fake()->url(),
            'end_date' => null,
        ]);

    $staticWebsiteCheckContentProcessorJob = new StaticWebsiteCheckContentProcessorJob($staticWebsiteCheck);
    $staticWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseHas(StaticWebsiteSnapshotEntry::class, [
        'url' => $url,
    ]);
});

it('marks the end date for a non existing entry if not in content', function (): void {
    $StaticWebsiteSnapshotEntry = StaticWebsiteSnapshotEntry::factory()->create(['end_date' => null]);

    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [],
            ],
        ]);

    $staticWebsiteCheckContentProcessorJob = new StaticWebsiteCheckContentProcessorJob($staticWebsiteCheck);
    $staticWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $StaticWebsiteSnapshotEntry->refresh();
    expect($StaticWebsiteSnapshotEntry->end_date)->not()->toBeNull();
});

it('silently fails on invalid content', function (): void {
    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->create([
            'content' => [fake()->word()],
        ]);

    $staticWebsiteCheckContentProcessorJob = new StaticWebsiteCheckContentProcessorJob($staticWebsiteCheck);
    $staticWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(StaticWebsiteSnapshotEntry::class, 0);
});

it('silently fails on invalid page content', function (): void {
    $staticWebsiteCheck = StaticWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [
                    fake()->word(),
                ],
            ],
        ]);

    $staticWebsiteCheckContentProcessorJob = new StaticWebsiteCheckContentProcessorJob($staticWebsiteCheck);
    $staticWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(StaticWebsiteSnapshotEntry::class, 0);
});
