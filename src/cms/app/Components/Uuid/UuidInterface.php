<?php

declare(strict_types=1);

namespace App\Components\Uuid;

interface UuidInterface
{
    public static function fromString(string $id): UuidInterface;

    public function __toString(): string;

    public function toString(): string;

    public function equals(UuidInterface $other): bool;
}
