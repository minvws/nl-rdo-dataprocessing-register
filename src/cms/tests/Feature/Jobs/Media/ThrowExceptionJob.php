<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Media;

use App\Jobs\Media\InvalidMimeTypeException;
use App\Jobs\Media\SkipBatchIfCancelledMiddleware;
use App\Vendor\MediaLibrary\Media;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ThrowExceptionJob implements ShouldQueue
{
    use Dispatchable;
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use SkipBatchIfCancelledMiddleware;

    public function __construct(
        protected Media $media,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        throw new InvalidMimeTypeException('exception');
    }
}
