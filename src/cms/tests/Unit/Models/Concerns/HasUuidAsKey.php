<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\HasSnapshots;
use Webmozart\Assert\InvalidArgumentException;

use function it;

it('throws an error if id is null', function (): void {
    $model = new class {
        // phpcs:ignore SlevomatCodingStandard.Namespaces.UseSpacing.IncorrectLinesCountBeforeFirstUse
        use HasSnapshots;

        public function getKey(): null
        {
            return null;
        }
    };

    $model->getId();
})->throws(InvalidArgumentException::class, 'id-attribute should be a string');
