<?php

declare(strict_types=1);

namespace App\ValueObjects\OneTimePassword;

use Webmozart\Assert\Assert;

readonly class Secret
{
    private function __construct(
        public string $secret,
    ) {
        Assert::notEmpty($this->secret);
    }

    public static function fromString(string $secret): self
    {
        return new self($secret);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->secret;
    }
}
