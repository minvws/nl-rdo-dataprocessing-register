<?php

declare(strict_types=1);

use App\Services\OneTimePassword\FakeOneTimePassword;
use App\ValueObjects\OneTimePassword\Code;
use App\ValueObjects\OneTimePassword\Secret;
use Illuminate\Support\Facades\App;

it('is always valid', function (): void {
    /** @var FakeOneTimePassword $fakeOneTimePassord */
    $fakeOneTimePassord = App::make(FakeOneTimePassword::class);

    $isValid = $fakeOneTimePassord->isCodeValid(Code::fromString(fake()->word()), Secret::fromString(fake()->word()));

    expect($isValid)
        ->toBe(true);
});
