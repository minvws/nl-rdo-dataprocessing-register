<?php

declare(strict_types=1);

use App\Services\DateFormatService;
use Carbon\CarbonImmutable;
use Tests\Helpers\ConfigTestHelper;

it('can format for filename', function (): void {
    $timezone = fake()->timezone();
    ConfigTestHelper::set('app.display_timezone', $timezone);
    $date = CarbonImmutable::instance(fake()->dateTime);

    $result = DateFormatService::toFilename($date);

    expect($result)
        ->toBe($date->setTimezone($timezone)->format('Y-m-d-H.i'));
});

it('can format for date', function (): void {
    $timezone = fake()->timezone();
    ConfigTestHelper::set('app.display_timezone', $timezone);
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

it('can format sentence', function (string $date, string $timezone, string $expected): void {
    ConfigTestHelper::set('app.display_timezone', $timezone);

    $result = DateFormatService::toSentence(CarbonImmutable::createFromFormat('d-m-Y H:i', $date));

    expect($result)
        ->toBe($expected);
})->with([
    ['1-1-2000 00:00', 'Europe/Amsterdam', '1 januari 2000 01:00'],
    ['4-11-2028 17:51', 'Europe/Amsterdam', '4 november 2028 18:51'],
    ['4-11-2028 17:51', 'Europe/Madrid', '4 november 2028 18:51'],
    ['28-5-2015 08:12', 'America/Bogota', '28 mei 2015 03:12'],
    ['14-9-2012 21:05', 'Australia/Brisbane', '15 september 2012 07:05'],
]);

it('can format sentence with null', function (): void {
    $result = DateFormatService::toSentence(null);

    expect($result)
        ->toBeNull();
});

it('can format for datetime', function (): void {
    $timezone = fake()->timezone();
    ConfigTestHelper::set('app.display_timezone', $timezone);
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

it('can format for valid until short', function (): void {
    $timezone = fake()->timezone();
    ConfigTestHelper::set('app.display_timezone', $timezone);
    $date = CarbonImmutable::instance(fake()->dateTime);

    $result = DateFormatService::forValidUntilShort($date);

    expect($result)
        ->toBe($date->setTimezone($timezone)->format('H:i d/m/Y'));
});

it('can get display timezone', function (): void {
    $timezone = fake()->timezone;
    ConfigTestHelper::set('app.display_timezone', $timezone);

    $result = DateFormatService::getDisplayTimezone();

    expect($result)
        ->toBe($timezone);
});
