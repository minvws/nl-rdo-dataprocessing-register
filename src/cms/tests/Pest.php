<?php

declare(strict_types=1);

use App\Components\Uuid\UuidInterface;
use Tests\Browser\BrowserTestCase;
use Tests\Feature\FeatureTestCase;
use Webmozart\Assert\Assert;

pest()
    ->extend(FeatureTestCase::class)
    ->in('Feature');

pest()
    ->extend(BrowserTestCase::class)
    ->in('Browser');

expect()
    ->intercept('toBe', UuidInterface::class, function (UuidInterface $expected): void {
        Assert::isInstanceOf($this->value, UuidInterface::class);

        expect($this->value->equals($expected))
            ->toBeTrue();
    });
