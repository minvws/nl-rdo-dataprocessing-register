<?php

declare(strict_types=1);

use App\Rules\IPRanges;

it('fails for invalid values', function (): void {
    $validated = true;
    (new IPRanges())->validate('test', 'this should not validate', static function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)
        ->toBeFalse();
});

it('allows valid values', function (string $ipRangesString): void {
    $validated = true;
    (new IPRanges())->validate('test', $ipRangesString, static function () use (&$validated): void {
        $validated = false;
    });

    expect($validated)
        ->toBeTrue();
})->with([
    '127.0.0.1',
    '127.0.0.*',
    '127.0.0.255/8',
    '196.168.1.2,127.0.0.1',
]);
