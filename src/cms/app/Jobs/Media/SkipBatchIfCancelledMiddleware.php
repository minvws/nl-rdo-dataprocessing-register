<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

trait SkipBatchIfCancelledMiddleware
{
    public function middleware(): array
    {
        return [new SkipIfBatchCancelled()];
    }
}
