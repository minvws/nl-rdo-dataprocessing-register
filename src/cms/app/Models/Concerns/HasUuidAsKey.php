<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Webmozart\Assert\Assert;

/**
 * @property string $id
 *
 * @deprecated replaced by HasUuidAsId
 */
trait HasUuidAsKey
{
    use HasUuids;

    public function getId(): string
    {
        $id = $this->getKey();
        Assert::string($id, 'id-attribute should be a string');

        return $id;
    }

    public function newUniqueId(): string
    {
        return Uuid::generate()->toString();
    }

    /**
     * @return array<string>
     */
    public function uniqueIds(): array
    {
        return ['id'];
    }
}
