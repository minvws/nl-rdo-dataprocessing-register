<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use Webmozart\Assert\Assert;

/**
 * @property UuidInterface $id
 */
trait HasUuidAsId
{
    public function initializeHasUuidAsId(): void
    {
        $this->usesUniqueIds = true;

        $this->mergeCasts([
            'id' => UuidCast::class,
        ]);
    }

    public function getId(): UuidInterface
    {
        $id = $this->getKey();
        Assert::isInstanceOf($id, UuidInterface::class);

        return $id;
    }

    /**
     * The Eloquent-Model uses a HasUniqueIds-trait, which forces to return a string here...
     *
     * @return UuidInterface
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
    public function newUniqueId()
    {
        return Uuid::generate();
    }

    /**
     * @return array<string>
     */
    public function uniqueIds(): array
    {
        return ['id'];
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): false
    {
        return false;
    }
}
