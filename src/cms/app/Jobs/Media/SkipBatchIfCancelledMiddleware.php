<?php

declare(strict_types=1);

namespace App\Jobs\Media;

use Illuminate\Queue\Middleware\SkipIfBatchCancelled;

trait SkipBatchIfCancelledMiddleware
{
    /**
     * @return array<object>
     */
    final public function middleware(): array
    {
        return [new SkipIfBatchCancelled()];
    }
}
