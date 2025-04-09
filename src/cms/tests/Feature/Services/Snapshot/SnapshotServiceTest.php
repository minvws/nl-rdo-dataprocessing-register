<?php

declare(strict_types=1);

use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Services\Snapshot\SnapshotService;

it('can count approved', function (): void {
    $approvedCount = fake()->randomDigit();

    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->count(fake()->randomDigit())
        ->create([
            'status' => fake()->randomElement([
                SnapshotApprovalStatus::DECLINED,
                SnapshotApprovalStatus::UNKNOWN,
            ]),
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->count($approvedCount)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);

    /** @var SnapshotService $snapshotService */
    $snapshotService = $this->app->get(SnapshotService::class);

    expect($snapshotService->countApproved($snapshot))
        ->toBe($approvedCount);
});

it('can count total', function (): void {
    $count = fake()->randomDigit();

    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->count($count)
        ->create([
            'status' => fake()->randomElement([
                SnapshotApprovalStatus::APPROVED,
                SnapshotApprovalStatus::DECLINED,
                SnapshotApprovalStatus::UNKNOWN,
            ]),
        ]);

    /** @var SnapshotService $snapshotService */
    $snapshotService = $this->app->get(SnapshotService::class);

    expect($snapshotService->countTotal($snapshot))
        ->toBe($count);
});

it('show is not approved if no approvals at all', function (): void {
    $snapshot = Snapshot::factory()->create();

    /** @var SnapshotService $snapshotService */
        $snapshotService = $this->app->get(SnapshotService::class);

    expect($snapshotService->isApproved($snapshot))
        ->toBeFalse();
});

it('show is not approved if not all approved', function (): void {
    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::DECLINED,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);

    /** @var SnapshotService $snapshotService */
    $snapshotService = $this->app->get(SnapshotService::class);

    expect($snapshotService->isApproved($snapshot))
        ->toBeFalse();
});

it('show is approved if all approved', function (): void {
    $snapshot = Snapshot::factory()->create();
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->create([
            'status' => SnapshotApprovalStatus::APPROVED,
        ]);

    /** @var SnapshotService $snapshotService */
    $snapshotService = $this->app->get(SnapshotService::class);

    expect($snapshotService->isApproved($snapshot))
        ->toBeTrue();
});
