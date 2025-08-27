<?php

declare(strict_types=1);

use App\Enums\Snapshot\MandateholderNotifyDirectly;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Mail\SnapshotApproval\BatchSignRequest;
use App\Mail\SnapshotApproval\SingleSignRequest;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\User;
use App\Services\Notification\SnapshotApprovalSignRequestMailer;
use Illuminate\Support\Facades\Mail;

it('can handle none', function (): void {
    $user = User::factory()
        ->create([
            'mandateholder_notify_directly' => MandateholderNotifyDirectly::NONE,
        ]);
    $snapshot = Snapshot::factory()
        ->create([
            'state' => Approved::class,
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertNothingQueued();
});

it('can handle a single notification', function (): void {
    $user = User::factory()
        ->create([
            'mandateholder_notify_directly' => MandateholderNotifyDirectly::SINGLE,
        ]);
    $snapshot = Snapshot::factory()
        ->create([
            'state' => Approved::class,
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertQueued(SingleSignRequest::class);
});

it('will not handle single notification if not approved', function (): void {
    $user = User::factory()
        ->create([
            'mandateholder_notify_directly' => MandateholderNotifyDirectly::SINGLE,
        ]);
    $snapshot = Snapshot::factory()
        ->create([
            'state' => fake()->randomElement([
                InReview::class,
                Established::class,
                Obsolete::class,
            ]),
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertNotQueued(SingleSignRequest::class);
});

it('will not handle single notification if profile not set to single', function (): void {
    $user = User::factory()
        ->create([
            'mandateholder_notify_directly' => fake()->randomElement([
                MandateholderNotifyDirectly::BATCH,
                MandateholderNotifyDirectly::NONE,
            ]),
        ]);
    $snapshot = Snapshot::factory()
        ->create([
            'state' => Approved::class,
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertNotQueued(SingleSignRequest::class);
});

it('can handle a batch notification', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create([
            'mandateholder_notify_directly' => MandateholderNotifyDirectly::BATCH,
        ]);
    $snapshot = Snapshot::factory()
        ->for($organisation)
        ->create([
            'state' => Approved::class,
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertQueued(BatchSignRequest::class);
});

it('will not handle batch notification if not approved', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create([
            'mandateholder_notify_directly' => MandateholderNotifyDirectly::BATCH,
        ]);
    $snapshot = Snapshot::factory()
        ->for($organisation)
        ->create([
            'state' => fake()->randomElement([
                InReview::class,
                Established::class,
                Obsolete::class,
            ]),
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertNotQueued(BatchSignRequest::class);
});

it('will not handle batch notification if profile not set to batch', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create([
            'mandateholder_notify_directly' => fake()->randomElement([
                MandateholderNotifyDirectly::SINGLE,
                MandateholderNotifyDirectly::NONE,
            ]),
        ]);
    $snapshot = Snapshot::factory()
        ->for($organisation)
        ->create([
            'state' => Approved::class,
        ]);
    $snapshotApproval = SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleNotification($user, $snapshotApproval);

    Mail::assertNotQueued(BatchSignRequest::class);
});

it('can batch weekly mails', function (): void {
    $organisation = Organisation::factory()
        ->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->create([
            'mandateholder_notify_directly' => fake()->randomElement([
                MandateholderNotifyDirectly::SINGLE,
                MandateholderNotifyDirectly::NONE,
            ]),
        ]);
    $snapshot = Snapshot::factory()
        ->for($organisation)
        ->create([
            'state' => Approved::class,
        ]);
    SnapshotApproval::factory()
        ->for($snapshot)
        ->for($user, 'assignedTo')
        ->create([
            'status' => SnapshotApprovalStatus::UNKNOWN,
        ]);

    Mail::fake();

    /** @var SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer */
    $snapshotApprovalSignRequestMailer = $this->app->get(SnapshotApprovalSignRequestMailer::class);
    $snapshotApprovalSignRequestMailer->handleWeekly();

    Mail::assertQueued(BatchSignRequest::class);
});
