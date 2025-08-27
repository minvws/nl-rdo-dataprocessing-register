<?php

declare(strict_types=1);

namespace App\Mail\Authentication;

use App\Config\Config;
use App\Enums\RouteName;
use App\Mail\Mailable;
use App\Models\UserLoginToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

use function __;

class PasswordLessLoginLink extends Mailable
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
            Config::integer('auth.passwordless.token_expiry_minutes') * 60,
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
        return __('auth.passwordless_login_subject');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.auth.passwordless_login');
    }
}
