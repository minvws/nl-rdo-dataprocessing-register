<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use Illuminate\Support\Str;
use JsonException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

abstract class Generator
{
    protected function convertMarkdownToHtml(?string $content): string
    {
        return Str::of((string) $content)->markdown()->toString();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @throws JsonException
     */
    protected function convertToFrontmatter(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
