<?php

declare(strict_types=1);

namespace App\Mail;

use App\Filament\Resources\DataBreachRecord\Pages\ViewDataBreachRecord;
use App\Models\DataBreachRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

use function __;

class DataBreachRecordApReportedNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;
    public DataBreachRecord $dataBreachRecord;

    public function __construct(DataBreachRecord $dataBreachRecord)
    {
        $this->dataBreachRecord = $dataBreachRecord;
        $this->link = ViewDataBreachRecord::getUrl([
            'record' => $dataBreachRecord,
            'tenant' => $dataBreachRecord->organisation,
        ]);
    }

    public function getSubject(): string
    {
        return __('data_breach_record.mail_notification_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.data_breach_record.ap_reported_notification');
    }
}
