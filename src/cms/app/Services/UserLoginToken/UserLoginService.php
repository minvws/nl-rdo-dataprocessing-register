<?php

declare(strict_types=1);

namespace App\Services\UserLoginToken;

use App\Components\Uuid\Uuid;
use App\Config\Config;
use App\Enums\Queue;
use App\Mail\Authentication\PasswordLessLoginLink;
use App\Models\User;
use App\Models\UserLoginToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function now;

class UserLoginService
{
    public function sendLoginLink(User $user, string $destination): void
    {
        $user->userLoginTokens()->delete();

        /** @var UserLoginToken $userLoginToken */
        $userLoginToken = $user->userLoginTokens()->create([
            'token' => Uuid::generate()->toString(),
            'expires_at' => now()->addMinutes(Config::integer('auth.passwordless.token_expiry_minutes')),
            'destination' => $this->removeAppUrlFromDestination($destination),
        ]);

        $mailable = (new PasswordLessLoginLink($userLoginToken))
            ->onQueue(Queue::HIGH);

        Mail::to($user->email)
            ->queue($mailable);
    }

    private function removeAppUrlFromDestination(string $destination): string
    {
        return Str::of($destination)
            ->remove(Config::string('app.url'))
            ->toString();
    }
}
