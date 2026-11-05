<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotDataSection;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Organisation;
use App\Models\Processor;
use App\Models\Receiver;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use App\ValueObjects\Markdown;

it('can render', function (SnapshotDataSection $snapshotDataSection): void {
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

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown($snapshot, $snapshotData->public_markdown, $snapshotDataSection);

    expect($markdown)->toMatchSnapshot();
})->with(SnapshotDataSection::cases());

it('can render even if no public_markdown', function (SnapshotDataSection $snapshotDataSection): void {
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

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown($snapshot, $snapshotData->public_markdown, $snapshotDataSection);

    expect($markdown)->toMatchSnapshot();
})->with(SnapshotDataSection::cases());

it('can render with related records', function (SnapshotDataSection $snapshotDataSection): void {
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
            'private_markdown' => 'Cupiditate iure voluptatem aspernatur.',
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $receiver->id,
            'snapshot_source_type' => $receiver::class,
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
        $avgResponsibleProcessingRecordSnapshot,
        $avgResponsibleProcessingRecordSnapshotData->public_markdown,
        $snapshotDataSection,
    );

    expect($markdown)->toMatchSnapshot();
})->with(SnapshotDataSection::cases());

it('can render but without related records if snapshot not yet established', function (SnapshotDataSection $snapshotDataSection): void {
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

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
        $avgResponsibleProcessingRecordSnapshot,
        $avgResponsibleProcessingRecordSnapshotData->public_markdown,
        $snapshotDataSection,
    );

    expect($markdown)->toMatchSnapshot();
})->with(SnapshotDataSection::cases());

it('renders related processors by name, without the email address stored in older snapshots', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create();

    $avgResponsibleProcessingRecordSnapshot = Snapshot::factory()
        ->for($avgResponsibleProcessingRecord, 'snapshotSource')
        ->recycle($organisation)
        ->create();

    $processor = Processor::factory()
        ->create(['name' => 'Acme BV']);
    $processorSnapshot = Snapshot::factory()
        ->for($processor, 'snapshotSource')
        ->recycle($organisation)
        ->create([
            'name' => 'Acme BV',
            'state' => Established::class,
        ]);
    SnapshotData::factory()
        ->for($processorSnapshot)
        ->create([
            'private_markdown' => 'Acme BV (info@acme.example)',
        ]);

    RelatedSnapshotSource::factory()
        ->create([
            'snapshot_id' => $avgResponsibleProcessingRecordSnapshot->id,
            'snapshot_source_id' => $processor->id,
            'snapshot_source_type' => $processor::class,
        ]);

    /** @var SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer */
    $snapshotDataMarkdownRenderer = $this->app->get(SnapshotDataMarkdownRenderer::class);

    $markdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
        $avgResponsibleProcessingRecordSnapshot,
        Markdown::fromString('<!--- #App\Models\Processor# --->'),
        SnapshotDataSection::PRIVATE,
    );

    expect($markdown)
        ->toContain('Acme BV')
        ->not->toContain('info@acme.example');
});
