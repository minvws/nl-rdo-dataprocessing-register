<?php

declare(strict_types=1);

use App\Filament\Resources\DataBreachRecord\Pages\ViewDataBreachRecord;
use App\Mail\DataBreachRecordApReportedNotification;
use App\Models\DataBreachRecord;
use Tests\Helpers\ConfigHelper;

it('has the correct content', function (): void {
    $dataBreachRecord = DataBreachRecord::factory()->create();
    $mailable = new DataBreachRecordApReportedNotification($dataBreachRecord);

    $link = ViewDataBreachRecord::getUrl([
        'record' => $dataBreachRecord,
        'tenant' => $dataBreachRecord->organisation,
    ]);

    $mailable->assertHasSubject(sprintf('[%s]: %s', ConfigHelper::get('app.name'), __('data_breach_record.mail_notification_subject')));
    $mailable->assertSeeInHtml(__('data_breach_record.mail_notification_text'));
    $mailable->assertSeeInHtml($link);
});
