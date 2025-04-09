<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Models\Contracts\SnapshotSource;
use Attribute;
use Webmozart\Assert\Assert;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class RelatedSnapshotSource
{
    /**
     * @param class-string<SnapshotSource> $snapshotSourceClass
     */
    public function __construct(string $snapshotSourceClass)
    {
        Assert::classExists($snapshotSourceClass);
    }
}
