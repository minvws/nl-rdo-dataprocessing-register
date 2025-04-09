<?php

declare(strict_types=1);

namespace App\Enums\Media;

enum MediaGroup: string
{
    case ATTACHMENTS = 'attachments';
    case ORGANISATION_POSTERS = 'organisation_posters';
    case PUBLIC_WEBSITE_TREE = 'public_website_tree';
}
