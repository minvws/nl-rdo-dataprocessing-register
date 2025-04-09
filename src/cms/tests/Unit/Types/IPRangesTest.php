<?php

declare(strict_types=1);

use App\Types\IPRanges;

it('denies IP address that are not explicity allowed', function ($ipRangesString): void {
    $ipRanges = IPRanges::make($ipRangesString);
    expect($ipRanges->contains("127.0.0.1"))->toBeFalse();
})->with(
    ["", "192.168.0.*", "127.0.0.2"],
);

it('allows IP address that are explicity allowed', function ($ipRangesString): void {
    $ipRanges = IPRanges::make($ipRangesString);
    expect($ipRanges->contains("127.0.0.1"))->toBeTrue();
})->with(
    ["127.0.0.1", "127.0.0.*", "127.0.0.255/8", "196.168.1.2,127.0.0.1"],
);
