<?php

declare(strict_types=1);

use App\Services\OneTimePassword\FakeOneTimePassword;
use Illuminate\Support\Facades\App;

it('is always valid', function (): void {
    /** @var FakeOneTimePassword $fakeOneTimePassord */
    $fakeOneTimePassord = App::make(FakeOneTimePassword::class);

    $isValid = $fakeOneTimePassord->isCodeValid(fake()->word(), fake()->word());

    expect($isValid)
        ->toBe(true);
});
