<?php

declare(strict_types=1);

use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\System;
use Carbon\CarbonImmutable;

it('can run the artisan sql-execute command', function (): void {
    $this->artisan('app:system-undouble')
        ->assertSuccessful();
});

it('will run with a single system', function (): void {
    $system = System::factory()->create();

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $system->refresh();
    $this->assertFalse($system->trashed());
});

it('will cleanup double if same description and organisation', function (): void {
    $system1 = System::factory()->create([
        'created_at' => CarbonImmutable::now()->subDays(1),
    ]);
    $system2 = System::factory()->create([
        'created_at' => CarbonImmutable::now(),
        'organisation_id' => $system1->organisation_id,
        'description' => $system1->description,
    ]);

    $now = CarbonImmutable::now();
    CarbonImmutable::setTestNow($now);

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $system2->refresh();
    $this->assertTrue($system2->trashed());
});

it('will not cleanup double if same description but different organisation', function (): void {
    $system1 = System::factory()->create();
    $system2 = System::factory()->create([
        'description' => $system1->description,
    ]);

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $system1->refresh();
    $this->assertFalse($system1->trashed());
    $system2->refresh();
    $this->assertFalse($system2->trashed());
});

it('will not cleanup double if different description but same organisation', function (): void {
    $system1 = System::factory()->create();
    $system2 = System::factory()->create([
        'organisation_id' => $system1->organisation_id,
    ]);

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $system1->refresh();
    $this->assertFalse($system1->trashed());
    $system2->refresh();
    $this->assertFalse($system2->trashed());
});

it('will not delete an existing linked snapshot', function (): void {
    $system1 = System::factory()->create([
        'created_at' => CarbonImmutable::now()->subDays(1),
    ]);
    System::factory()->create([
        'created_at' => CarbonImmutable::now(),
        'organisation_id' => $system1->organisation_id,
        'description' => $system1->description,
    ]);
    $snapshot = Snapshot::factory()->create();
    $relatedSnapshotSource = RelatedSnapshotSource::factory()->create([
        'snapshot_id' => $snapshot->id,
        'snapshot_source_type' => System::class,
        'snapshot_source_id' => $system1->id,
    ]);

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $this->assertDatabaseHas(RelatedSnapshotSource::class, [
        'id' => $relatedSnapshotSource->id,
    ]);
});

it('will delete the existing linked snapshot for the double', function (): void {
    $system1 = System::factory()->create([
        'created_at' => CarbonImmutable::now()->subDays(1),
    ]);
    $system2 = System::factory()->create([
        'created_at' => CarbonImmutable::now(),
        'organisation_id' => $system1->organisation_id,
        'description' => $system1->description,
    ]);
    $snapshot = Snapshot::factory()->create();
    $relatedSnapshotSource1 = RelatedSnapshotSource::factory()->create([
        'snapshot_id' => $snapshot->id,
        'snapshot_source_type' => System::class,
        'snapshot_source_id' => $system1->id,
    ]);
    $relatedSnapshotSource2 = RelatedSnapshotSource::factory()->create([
        'snapshot_id' => $snapshot->id,
        'snapshot_source_type' => System::class,
        'snapshot_source_id' => $system2->id,
    ]);

    $this->artisan('app:system-undouble')
        ->assertSuccessful();

    $this->assertDatabaseHas(RelatedSnapshotSource::class, [
        'id' => $relatedSnapshotSource1->id,
    ]);
    $this->assertDatabaseMissing(RelatedSnapshotSource::class, [
        'id' => $relatedSnapshotSource2->id,
    ]);
});
