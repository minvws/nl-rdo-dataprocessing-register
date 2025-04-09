<?php

declare(strict_types=1);

use App\Mail\Authentication\PasswordLessLoginLink;
use App\Models\UserLoginToken;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Request;
use Tests\Helpers\ConfigHelper;

it('contains the correct token', function (): void {
    $userLoginToken = UserLoginToken::factory()->create();

    $mail = new PasswordLessLoginLink($userLoginToken);
    $request = Request::create($mail->link);

    expect($request->get('token'))
        ->toBe($userLoginToken->token);
});

it('contains the correct expiry time', function (): void {
    $userLoginToken = UserLoginToken::factory()->create();
    $tokenExpiryInMinutes = fake()->numberBetween(1, 9);

    ConfigHelper::set('auth.passwordless.token_expiry_minutes', $tokenExpiryInMinutes);

    $mail = new PasswordLessLoginLink($userLoginToken);
    $request = Request::create($mail->link);

    expect((int) $request->get('expires'))
        ->toBe(CarbonImmutable::now()->timestamp + $tokenExpiryInMinutes * 60);
});
