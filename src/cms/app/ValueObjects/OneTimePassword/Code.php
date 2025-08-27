<?php

declare(strict_types=1);

namespace App\ValueObjects\OneTimePassword;

use Webmozart\Assert\Assert;

readonly class Code
{
    private function __construct(
        public string $code,
    ) {
        Assert::notEmpty($this->code);
    }

    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->code;
    }
}
