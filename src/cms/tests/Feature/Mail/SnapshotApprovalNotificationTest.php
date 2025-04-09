<?php

declare(strict_types=1);

use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Mail\SnapshotApprovalNotification;
use App\Models\SnapshotApproval;
use Tests\Helpers\ConfigHelper;

it('has the correct content', function (): void {
    $snapshotApproval = SnapshotApproval::factory()->create();
    $mailable = new SnapshotApprovalNotification($snapshotApproval);

    $link = ViewSnapshot::getUrl([
        'record' => $snapshotApproval->snapshot,
        'tenant' => $snapshotApproval->snapshot->organisation,
        'tab' => sprintf('-%s-tab', ViewSnapshot::TAB_ID_APPROVAL),
    ]);

    $mailable->assertHasSubject(sprintf('[%s]: %s', ConfigHelper::get('app.name'), __('snapshot_approval.mail_notification_subject')));
    $mailable->assertSeeInHtml(__('snapshot_approval.mail_notification_text'));
    $mailable->assertSeeInHtml($link);
});
