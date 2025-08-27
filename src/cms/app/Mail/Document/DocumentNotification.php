<?php

declare(strict_types=1);

namespace App\Mail\Document;

use App\Filament\Resources\DocumentResource\Pages\EditDocument;
use App\Mail\Mailable;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

use function __;

class DocumentNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;

    public function __construct(
        public Document $document,
    ) {
        $this->link = EditDocument::getUrl([
            'record' => $document,
            'tenant' => $document->organisation,
        ]);
    }

    public function getLogContext(): array
    {
        return [
            'document_id' => $this->document->id->toString(),
        ];
    }

    public function getSubject(): string
    {
        return __('document.mail_notification_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.document.notification');
    }
}
