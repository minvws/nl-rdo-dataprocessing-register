<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use App\Vendor\MediaLibrary\Media;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkMediaUploadAsValidated implements ShouldQueue
{
    use Dispatchable;
    use Batchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use SkipBatchIfCancelledMiddleware;

    public function __construct(
        private readonly Media $media,
    ) {
    }

    public function handle(): void
    {
        if ($this->media->validated_at !== null) {
            return;
        }

        $this->media->validated_at = CarbonImmutable::now();
        $this->media->save();
    }
}
