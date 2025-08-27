<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Support\Stringable;

readonly class Markdown
{
    private function __construct(
        private Stringable $markdown,
    ) {
    }

    public static function fromString(string $string): self
    {
        return new self(new Stringable($string));
    }

    public function toHtml(): string
    {
        $options = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ];

        return $this->markdown
            ->markdown($options)
            ->toString();
    }

    public function toString(): string
    {
        return $this->markdown->toString();
    }
}
