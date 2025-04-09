<?php

declare(strict_types=1);

use App\Enums\MarkdownField;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Receiver;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;

it('can render', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create();
    $snapshotData = SnapshotData::factory()
        ->for($snapshot)
        ->create([
            'public_markdown' => 'Nam quae temporibus et molestiae voluptatibus enim.',
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotData($snapshotData, MarkdownField::PUBLIC_MARKDOWN);

    expect($markdown)->toMatchSnapshot();
});

it('can render even if no public_markdown', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $snapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create();
    $snapshotData = SnapshotData::factory()
        ->for($snapshot)
        ->create([
            'public_markdown' => null,
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotData($snapshotData, MarkdownField::PUBLIC_MARKDOWN);

    expect($markdown)->toMatchSnapshot();
});

it('can render with related records', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshotData = SnapshotData::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'public_markdown' => 'Nam quae temporibus et molestiae voluptatibus enim.<!--- #App\Models\Receiver# --->',
        ]);

    $receiver = Receiver::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->create();
    $receiverSnapshot = Snapshot::factory()
        ->for($receiver, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($receiverSnapshot)
        ->create([
            'public_markdown' => 'Doloribus eum qui fugit hic voluptas aliquam id.',
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $receiver->id,
            'snapshot_source_type' => $receiver::class,
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotData(
        $avgResponsibleProcessingRecordSnapshotData,
        MarkdownField::PUBLIC_MARKDOWN,
    );

    expect($markdown)->toMatchSnapshot();
});

it('can render but without related records if snapshot not yet established', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create();
    $avgResponsibleProcessingRecordSnapshotData = SnapshotData::factory()
        ->for($avgResponsibleProcessingRecordSnapshot)
        ->create([
            'public_markdown' => 'Nam quae temporibus et molestiae voluptatibus enim.<!--- #App\Models\Receiver# --->',
        ]);

    $receiver = Receiver::factory()
        ->hasAttached($avgResponsibleProcessingRecord)
        ->create();
    $receiverSnapshot = Snapshot::factory()
        ->for($receiver, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'state' => InReview::class,
        ]);
    SnapshotData::factory()
        ->for($receiverSnapshot)
        ->create([
            'public_markdown' => 'Doloribus eum qui fugit hic voluptas aliquam id.',
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $receiver->id,
            'snapshot_source_type' => $receiver::class,
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotData(
        $avgResponsibleProcessingRecordSnapshotData,
        MarkdownField::PUBLIC_MARKDOWN,
    );

    expect($markdown)->toMatchSnapshot();
});
