<?php

declare(strict_types=1);

namespace App\Components\Uuid;

use InvalidArgumentException;
use JsonSerializable;
use Livewire\Wireable;
use Ramsey\Uuid\Exception\UuidExceptionInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface as RamseyUuidInterface;
use Webmozart\Assert\Assert;

class Uuid implements JsonSerializable, UuidInterface, Wireable
{
    private RamseyUuidInterface $uuid;

    private function __construct(
        string $uuid,
    ) {
        $this->uuid = $this->createRamseyUuidFromString($uuid);
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(UuidInterface $other): bool
    {
        return $this->uuid->equals($this->createRamseyUuidFromString($other->toString()));
    }

    public static function generate(): UuidInterface
    {
        return new self(RamseyUuid::uuid7()->toString());
    }

    /**
     * @return array{id: string}
     */
    public function jsonSerialize(): array
    {
        return ['id' => $this->toString()];
    }

    /**
     * @return array{id: string}
     */
    public function toLivewire(): array
    {
        return ['id' => $this->toString()];
    }

    public static function fromLivewire(mixed $value): self
    {
        Assert::isArray($value);
        Assert::keyExists($value, 'id');
        Assert::string($value['id']);

        return new self($value['id']);
    }

    private function createRamseyUuidFromString(string $uuid): RamseyUuidInterface
    {
        try {
            return RamseyUuid::fromString($uuid);
        } catch (UuidExceptionInterface $uuidException) {
            throw new InvalidArgumentException($uuidException->getMessage(), $uuidException->getCode(), $uuidException);
        }
    }
}
