<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use App\Services\Media\ExifToolService;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Process\Exceptions\ProcessFailedException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PruneMetaData implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use SkipBatchIfCancelledMiddleware;

    public function __construct(
        private readonly Media $media,
    ) {
    }

    /**
     * @throws ProcessFailedException
     */
    public function handle(
        ExifToolService $exifToolService,
    ): void {
        $exifToolService->pruneExifData($this->media->getPath());
    }
}
