<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Concerns;

use App\Exceptions\AppException;
use App\Services\PublicWebsite\BuildException;
use RuntimeException;

use function it;

it('will rethrow the exception with the correct type', function (): void {
    throw BuildException::fromThrowable(new RuntimeException());
})->expectException(AppException::class);

it('will rethrow the exception with the correct message', function (): void {
    throw BuildException::fromThrowable(new RuntimeException('message'));
})->expectExceptionMessage('message');

it('will rethrow the exception with the correct code', function (): void {
    throw BuildException::fromThrowable(new RuntimeException(code: 123));
})->expectExceptionCode(123);
