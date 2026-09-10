<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Mail\Authentication\PasswordLessLoginLink;
use App\Models\User;
use App\Models\UserLoginToken;
use App\Services\UserLoginToken\UserLoginService;
use Pest\Browser\Api\AwaitableWebpage;

use function __;
use function app;
use function visit;

/**
 * Logs a user in through the real passwordless flow.
 *
 * There is no browser equivalent of actingAs(): the browser talks HTTP, so it has to walk the same
 * route a person walks. Every visit() opens a fresh browser context, and therefore a fresh cookie
 * jar, so logging in and asserting have to happen in one chain.
 */
trait WithBrowserAuthentication
{
    private const string ONE_TIME_PASSWORD_CODE = '123456';

    private const string ONE_TIME_PASSWORD_FIELD = '#form\\.code';

    /**
     * Log the given user in and end up on the given destination.
     */
    final protected function loginAs(User $user, string $destination): AwaitableWebpage
    {
        return visit($this->passwordLessLoginUrl($user, $destination))
            ->click(__('auth.confirm_login'))
            ->assertPresent(self::ONE_TIME_PASSWORD_FIELD)
            ->typeSlowly(self::ONE_TIME_PASSWORD_FIELD, self::ONE_TIME_PASSWORD_CODE)
            ->keys(self::ONE_TIME_PASSWORD_FIELD, 'Enter');
    }

    /**
     * Create a login token the same way the application does, and build the link the mail would carry.
     */
    private function passwordLessLoginUrl(User $user, string $destination): string
    {
        app(UserLoginService::class)->sendPasswordLessLoginLink($user, $destination);

        /** @var UserLoginToken $userLoginToken */
        $userLoginToken = $user->userLoginTokens()->sole();

        return new PasswordLessLoginLink($userLoginToken)->link;
    }
}
