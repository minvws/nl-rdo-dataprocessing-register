<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Concerns;

use App\Exceptions\AppException;
use App\Services\PublicWebsite\BuildException;
use RuntimeException;

use function it;

it('will rethrow the exception with the correct message', function (): void {
    throw BuildException::fromThrowable(new RuntimeException('message', 123));
})->throws(AppException::class, 'message', 123);
