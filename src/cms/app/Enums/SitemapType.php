<?php

declare(strict_types=1);

namespace App\Enums;

enum SitemapType: string
{
    case PAGE = 'page';
    case PROCESSING_RECORD = 'processing-record';
}
