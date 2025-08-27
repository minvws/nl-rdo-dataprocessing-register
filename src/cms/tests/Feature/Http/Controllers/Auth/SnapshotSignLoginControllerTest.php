<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\RouteName;
use App\Filament\Resources\PersonalSnapshotApprovalResource;
use App\Filament\Resources\PersonalSnapshotApprovalResource\Pages\ListPersonalSnapshotApprovalItems;
use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Mail\Authentication\SnapshotSignLoginLink;
use App\Models\Organisation;
use App\Models\Snapshot;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use function it;
use function sprintf;

it('batch forbidden without valid signature', function (): void {
    $this->get(URL::route(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN))
        ->assertForbidden();
});

it('batch not found without organisation or user', function (): void {
    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN))
        ->assertNotFound();
});

it('batch not found without organisation', function (): void {
    $user = User::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN, [
        'user_id' => $user->id->toString(),
    ]))
        ->assertNotFound();
});

it('batch not found without user', function (): void {
    $organisation = Organisation::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN, [
        'organisation_id' => $organisation->id->toString(),
    ]))
        ->assertNotFound();
});

it('batch valid with snapshot & user', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN, [
        'organisation_id' => $organisation->id->toString(),
        'user_id' => $user->id->toString(),
    ]))
        ->assertOk()
        ->assertViewIs('auth.snapshot_sign');
});

it('batch redirects to snapshot if user already logged in', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()->create();

    $this->be($user);

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN, [
        'organisation_id' => $organisation->id->toString(),
        'user_id' => $user->id->toString(),
    ]))
        ->assertRedirect(PersonalSnapshotApprovalResource::getUrl(parameters: [
            'tenant' => $organisation,
            'activeTab' => ListPersonalSnapshotApprovalItems::TAB_ID_UNREVIEWED,
        ]));
});

it('batch redirect on success', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()->create();

    $route = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_LOGIN, [
        'organisation_id' => $organisation->id->toString(),
        'user_id' => $user->id->toString(),
    ]);
    $this->post($route)
        ->assertRedirect($route)
        ->assertSessionHas('snapshot_sign_login_link_success');
});

it('batch will send the correct mail', function (): void {
    Mail::fake();

    $organisation = Organisation::factory()->create();
    $user = User::factory()->create();

    $route = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_LOGIN, [
        'organisation_id' => $organisation->id->toString(),
        'user_id' => $user->id->toString(),
    ]);
    $this->post($route);

    Mail::assertQueued(
        SnapshotSignLoginLink::class,
        static function (SnapshotSignLoginLink $mail) use ($user): bool {
            return $mail->to[0]['address'] === $user->email;
        },
    );
});

it('single forbidden without valid signature', function (): void {
    $this->get(URL::route(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN))
        ->assertForbidden();
});

it('single not found without snapshot or user', function (): void {
    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN))
        ->assertNotFound();
});

it('single not found without snapshot', function (): void {
    $user = User::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN, [
        'user_id' => $user->id->toString(),
    ]))
        ->assertNotFound();
});

it('single not found without user', function (): void {
    $snapshot = Snapshot::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN, [
        'snapshot_id' => $snapshot->id->toString(),
    ]))
        ->assertNotFound();
});

it('single valid with snapshot & user', function (): void {
    $snapshot = Snapshot::factory()->create();
    $user = User::factory()->create();

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN, [
        'snapshot_id' => $snapshot->id->toString(),
        'user_id' => $user->id->toString(),
    ]))
        ->assertOk()
        ->assertViewIs('auth.snapshot_sign');
});

it('single redirects to snapshot if user already logged in', function (): void {
    $snapshot = Snapshot::factory()->create();
    $user = User::factory()->create();

    $this->be($user);

    $this->get(URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN, [
        'snapshot_id' => $snapshot->id->toString(),
        'user_id' => $user->id->toString(),
    ]))
        ->assertRedirect(ViewSnapshot::getUrl([
            'record' => $snapshot,
            'tenant' => $snapshot->organisation,
            'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_INFO),
        ]));
});

it('single redirect on success', function (): void {
    $snapshot = Snapshot::factory()->create();
    $user = User::factory()->create();

    $route = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_LOGIN, [
        'snapshot_id' => $snapshot->id->toString(),
        'user_id' => $user->id->toString(),
    ]);
    $this->post($route)
        ->assertRedirect($route)
        ->assertSessionHas('snapshot_sign_login_link_success');
});

it('single will send the correct mail', function (): void {
    Mail::fake();

    $snapshot = Snapshot::factory()->create();
    $user = User::factory()->create();

    $route = URL::signedRoute(RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_LOGIN, [
        'snapshot_id' => $snapshot->id->toString(),
        'user_id' => $user->id->toString(),
    ]);
    $this->post($route);

    Mail::assertQueued(
        SnapshotSignLoginLink::class,
        static function (SnapshotSignLoginLink $mail) use ($user): bool {
            return $mail->to[0]['address'] === $user->email;
        },
    );
});
