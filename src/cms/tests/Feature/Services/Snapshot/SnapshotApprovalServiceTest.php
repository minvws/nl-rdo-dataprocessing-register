<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Enums\Snapshot\SnapshotApprovalLogMessageType;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Mail\SnapshotApprovalNotification;
use App\Mail\SnapshotApprovalRequest;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\SnapshotApprovalLog;
use App\Models\User;
use App\Services\Snapshot\SnapshotApprovalService;
use Illuminate\Support\Facades\Mail;

it('can create', function (): void {
    $snapshot = Snapshot::factory()->create();
    $user = User::factory()->create();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->create($snapshot, $user, $user);

    $this->assertDatabaseHas(SnapshotApproval::class, [
        'snapshot_id' => $snapshot->id,
    ]);
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
    ]);
});

it('can delete', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
    ]);
    $user = User::factory()->create();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->delete($snapshotApproval, $user);

    $this->assertDatabaseMissing(SnapshotApproval::class, [
        'id' => $snapshotApproval->id,
    ]);
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
    ]);
});

it('will notify on request', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
    ]);
    $user = User::factory()->create();
    Mail::fake();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->notify($snapshotApproval, $user);

    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
    ]);
    Mail::assertQueued(SnapshotApprovalRequest::class);
});

it('can set status to approved', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::UNKNOWN,
    ]);
    $user = User::factory()->create();
    Mail::fake();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->setStatus($user, $snapshotApproval, SnapshotApprovalStatus::APPROVED);

    $snapshotApproval->refresh();
    expect($snapshotApproval->status)
        ->toBe(SnapshotApprovalStatus::APPROVED);
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
        'user_id' => $user->id,
        'message->type' => SnapshotApprovalLogMessageType::APPROVAL_UPDATE,
    ]);

    Mail::assertNotSent(SnapshotApprovalNotification::class);
    $this->assertDatabaseMissing(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
        'message->type' => SnapshotApprovalLogMessageType::APPROVAL_NOTIFY,
    ]);
});

it('can set status to approved with notification to other approvals', function (): void {
    $organisation = Organisation::factory()->create();

    $snapshot = Snapshot::factory()
        ->for($organisation)
        ->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::UNKNOWN,
    ]);
    $user1 = User::factory()->hasAttached($organisation)->create();
    $user2 = User::factory()->hasAttached($organisation)->create();
    $user2->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    Mail::fake();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->setStatus($user1, $snapshotApproval, SnapshotApprovalStatus::APPROVED);

    $snapshotApproval->refresh();
    expect($snapshotApproval->status)
        ->toBe(SnapshotApprovalStatus::APPROVED);
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
        'user_id' => $user1->id,
        'message->type' => SnapshotApprovalLogMessageType::APPROVAL_UPDATE,
    ]);

    Mail::assertQueued(
        SnapshotApprovalNotification::class,
        static function (SnapshotApprovalNotification $mail) use ($user2): bool {
            return $mail->hasTo($user2->email);
        },
    );
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
        'user_id' => $user1->id,
        'message->type' => SnapshotApprovalLogMessageType::APPROVAL_NOTIFY,
    ]);
});

it('can set status to declined', function (): void {
    $snapshot = Snapshot::factory()->create();
    $snapshotApproval = SnapshotApproval::factory()->create([
        'snapshot_id' => $snapshot->id,
        'status' => SnapshotApprovalStatus::UNKNOWN,
    ]);
    $user = User::factory()->create();

    /** @var SnapshotApprovalService $snapshotApprovalService */
    $snapshotApprovalService = $this->app->get(SnapshotApprovalService::class);
    $snapshotApprovalService->setStatus($user, $snapshotApproval, SnapshotApprovalStatus::DECLINED, fake()->sentence());

    $snapshotApproval->refresh();
    expect($snapshotApproval->status)
        ->toBe(SnapshotApprovalStatus::DECLINED);
    $this->assertDatabaseHas(SnapshotApprovalLog::class, [
        'snapshot_id' => $snapshot->id,
    ]);
});
