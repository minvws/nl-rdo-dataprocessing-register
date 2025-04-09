<?php

declare(strict_types=1);

namespace App\Services\Media;

use function mime_content_type;

class MimeTypeService
{
    public function getMimeType(string $path): false|string
    {
        return mime_content_type($path);
    }
}
