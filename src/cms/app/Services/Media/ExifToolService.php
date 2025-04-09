<?php

declare(strict_types=1);

namespace App\Services\Media;

use Illuminate\Support\Facades\Process;

class ExifToolService
{
    public function pruneExifData(string $pathToFile): void
    {
        Process::run(['exiftool', '-all=', $pathToFile]);
    }
}
