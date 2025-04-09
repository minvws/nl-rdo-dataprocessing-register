<?php

declare(strict_types=1);

namespace App\Mail\Authentication;

use App\Config\Config;
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

    public function __construct(UserLoginToken $userLoginToken)
    {
        $this->link = URL::signedRoute(
            'passwordless-login.validate',
            [
                'token' => $userLoginToken->token,
            ],
            Config::integer('auth.passwordless.token_expiry_minutes') * 60,
        );
    }

    public function getSubject(): string
    {
        return __('auth.passwordless_login_link');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.passwordless-login.login');
    }
}
