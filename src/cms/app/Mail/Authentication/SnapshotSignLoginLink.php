<?php

declare(strict_types=1);

namespace App\Mail\Authentication;

use App\Enums\RouteName;
use App\Mail\Mailable;
use App\Models\UserLoginToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

use function __;

class SnapshotSignLoginLink extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $link;

    public function __construct(
        public UserLoginToken $userLoginToken,
    ) {
        $this->link = URL::signedRoute(
            RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME,
            ['token' => $userLoginToken->token],
            $userLoginToken->expires_at,
        );
    }

    public function getLogContext(): array
    {
        return [
            'expires_at' => $this->userLoginToken->expires_at->toString(),
        ];
    }

    public function getSubject(): string
    {
        return __('auth.snapshot_sign_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.auth.snapshot_sign_login');
    }
}
