<?php

declare(strict_types=1);

use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Mail\SnapshotApproval\ApprovalRequest;
use App\Models\Snapshot;
use Tests\Helpers\ConfigTestHelper;

it('has the correct content', function (): void {
    $snapshot = Snapshot::factory()->create();
    $mailable = new ApprovalRequest($snapshot);

    $link = ViewSnapshot::getUrl([
        'record' => $snapshot,
        'tenant' => $snapshot->organisation,
        'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_INFO),
    ]);

    $mailable->assertHasSubject(
        sprintf('[%s]: %s', ConfigTestHelper::get('app.name'), __('snapshot_approval.mail_approval_request_subject')),
    );
    $mailable->assertSeeInHtml(__('snapshot_approval.mail_approval_request_text'));
    $mailable->assertSeeInHtml($link);
});

it('has the correct log context', function (): void {
    $snapshot = Snapshot::factory()->create();
    $mailable = new ApprovalRequest($snapshot);

    expect($mailable->getLogContext())
        ->toBe([
            'snapshot_id' => $snapshot->id->toString(),
        ]);
});
