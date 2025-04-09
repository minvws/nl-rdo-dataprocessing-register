<?php

declare(strict_types=1);

namespace App\Mail\User;

use App\Config\Config;
use App\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

use function __;

class UserCreatedMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;

    public function __construct()
    {
        $this->link = Config::string('app.url');
    }

    public function getSubject(): string
    {
        return __('email.user_created.subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.nl.user_created');
    }
}
