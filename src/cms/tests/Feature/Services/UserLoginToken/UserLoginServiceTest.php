<?php

declare(strict_types=1);

use App\Enums\Queue;
use App\Mail\Authentication\PasswordLessLoginLink;
use App\Models\User;
use App\Models\UserLoginToken;
use App\Services\UserLoginToken\UserLoginService;
use Illuminate\Support\Facades\Mail;

it('will store a token for a user', function (): void {
    $user = User::factory()->create();

    /** @var UserLoginService $userLoginService */
    $userLoginService = $this->app->get(UserLoginService::class);

    $userLoginService->sendPasswordLessLoginLink($user, '/');

    $this->assertDatabaseCount(UserLoginToken::class, 1);
});

it('will queue the correct mail to the correct mailaddress and on the correct queue', function (): void {
    Mail::fake();

    $user = User::factory()->create();

    /** @var UserLoginService $userLoginService */
    $userLoginService = $this->app->get(UserLoginService::class);

    $userLoginService->sendPasswordLessLoginLink($user, '/');

    Mail::assertQueued(PasswordLessLoginLink::class, static function (PasswordLessLoginLink $mail) use ($user): bool {
        return $mail->hasTo($user->email) && $mail->queue === Queue::HIGH->value;
    });
});

it('will only store one token for a single user', function (): void {
    $user = User::factory()->create();

    /** @var UserLoginService $userLoginService */
    $userLoginService = $this->app->get(UserLoginService::class);

    $userLoginService->sendPasswordLessLoginLink($user, '/');
    $userLoginService->sendPasswordLessLoginLink($user, '/');

    $this->assertDatabaseCount(UserLoginToken::class, 1);
});

it('can handle multiple tokens for multiple users', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    /** @var UserLoginService $userLoginService */
    $userLoginService = $this->app->get(UserLoginService::class);

    $userLoginService->sendPasswordLessLoginLink($user1, '/');
    $userLoginService->sendPasswordLessLoginLink($user2, '/');

    $this->assertDatabaseCount(UserLoginToken::class, 2);
});
