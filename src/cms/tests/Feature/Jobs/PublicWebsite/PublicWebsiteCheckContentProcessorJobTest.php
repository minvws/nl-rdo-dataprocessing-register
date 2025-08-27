<?php

declare(strict_types=1);

use App\Jobs\PublicWebsite\PublicWebsiteCheckContentProcessorJob;
use App\Models\PublicWebsiteCheck;
use App\Models\PublicWebsiteSnapshotEntry;
use App\Models\Snapshot;
use Psr\Log\NullLogger;

it('reads the content and creates a public-website snapshot-entry', function (): void {
    $snapshot = Snapshot::factory()->create();

    $publicWebsiteCheck = PublicWebsiteCheck::factory()
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

    $this->assertDatabaseCount(PublicWebsiteSnapshotEntry::class, 0);

    $publicWebsiteCheckContentProcessorJob = new PublicWebsiteCheckContentProcessorJob($publicWebsiteCheck);
    $publicWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(PublicWebsiteSnapshotEntry::class, 1);
});

it('marks the end date for a non existing entry if not in content', function (): void {
    $publicWebsiteSnapshotEntry = PublicWebsiteSnapshotEntry::factory()->create(['end_date' => null]);

    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [],
            ],
        ]);

    $publicWebsiteCheckContentProcessorJob = new PublicWebsiteCheckContentProcessorJob($publicWebsiteCheck);
    $publicWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $publicWebsiteSnapshotEntry->refresh();
    expect($publicWebsiteSnapshotEntry->end_date)->not()->toBeNull();
});

it('silently fails on invalid content', function (): void {
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->create([
            'content' => [fake()->word()],
        ]);

    $publicWebsiteCheckContentProcessorJob = new PublicWebsiteCheckContentProcessorJob($publicWebsiteCheck);
    $publicWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(PublicWebsiteSnapshotEntry::class, 0);
});

it('silently fails on invalid page content', function (): void {
    $publicWebsiteCheck = PublicWebsiteCheck::factory()
        ->create([
            'content' => [
                'pages' => [
                    fake()->word(),
                ],
            ],
        ]);

    $publicWebsiteCheckContentProcessorJob = new PublicWebsiteCheckContentProcessorJob($publicWebsiteCheck);
    $publicWebsiteCheckContentProcessorJob->handle(new NullLogger());

    $this->assertDatabaseCount(PublicWebsiteSnapshotEntry::class, 0);
});
