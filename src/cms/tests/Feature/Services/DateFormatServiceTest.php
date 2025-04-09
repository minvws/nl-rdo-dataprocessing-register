<?php

declare(strict_types=1);

use App\Services\DateFormatService;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigHelper;

it('can format for filename', function (): void {
    $timezone = fake()->timezone();
    ConfigHelper::set('app.display_timezone', $timezone);
    $date = CarbonImmutable::instance(fake()->dateTime);

    $result = DateFormatService::toFilename($date);

    expect($result)
        ->toBe($date->setTimezone($timezone)->format('Y-m-d-H.i'));
});

it('can format for date', function (): void {
    $timezone = fake()->timezone();
    ConfigHelper::set('app.display_timezone', $timezone);
    $date = CarbonImmutable::instance(fake()->dateTime);

    $result = DateFormatService::toDate($date);

    expect($result)
        ->toBe($date->setTimezone($timezone)->format('d-m-Y'));
});

it('can format for date with null', function (): void {
    $result = DateFormatService::toDate(null);

    expect($result)
        ->toBeNull();
});

it('can format for datetime', function (): void {
    $timezone = fake()->timezone();
    ConfigHelper::set('app.display_timezone', $timezone);
    $date = CarbonImmutable::instance(fake()->dateTime);

    $result = DateFormatService::toDateTime($date);

    expect($result)
        ->toBe($date->setTimezone($timezone)->format('d-m-Y H:i'));
});

it('can format for datetime with null', function (): void {
    $result = DateFormatService::toDateTime(null);

    expect($result)
        ->toBeNull();
});
