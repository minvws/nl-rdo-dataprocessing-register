<?php

declare(strict_types=1);

namespace App\Enums;

enum MarkdownField: string
{
    case PUBLIC_MARKDOWN = 'public_markdown';
    case PRIVATE_MARKDOWN = 'private_markdown';
}
