<?php

declare(strict_types=1);

use Illuminate\Support\Str;

function single_line_escaped_markdown(?string $value): ?string
{
    if ($value === null || Str::length($value) === 0) {
        return '-';
    }

    return preg_replace("/\r|\n/", "", nl2br(e($value)));
}
