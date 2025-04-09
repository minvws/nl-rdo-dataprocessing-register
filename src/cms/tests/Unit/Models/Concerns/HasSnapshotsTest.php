<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Concerns;

use App\Models\Concerns\HasSnapshots;
use Webmozart\Assert\InvalidArgumentException;

use function expect;
use function it;

it('returns the name attribute', function (): void {
    $model = new class {
        // phpcs:ignore SlevomatCodingStandard.Namespaces.UseSpacing.IncorrectLinesCountBeforeFirstUse
        use HasSnapshots;

        public function getAttribute(string $attribute): string
        {
            return $attribute;
        }
    };

    expect($model->getDisplayName())
        ->toBe('name');
});

it('throws an error if name is null', function (): void {
    $model = new class {
        // phpcs:ignore SlevomatCodingStandard.Namespaces.UseSpacing.IncorrectLinesCountBeforeFirstUse
        use HasSnapshots;

        public function getAttribute(string $attribute): string | null
        {
            if ($attribute === 'name') {
                return null;
            }

            return 'something';
        }
    };

    $model->getDisplayName();
})->throws(InvalidArgumentException::class, 'name-attribute should be a string');
