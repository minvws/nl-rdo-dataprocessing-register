<?php

declare(strict_types=1);

namespace App\Jobs\PublicWebsite;

use App\Enums\Queue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Process;
use Psr\Log\LoggerInterface;

class AfterBuildHookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(
        private readonly string $afterBuildHook,
    ) {
        $this->onQueue(Queue::LOW);
    }

    public function handle(LoggerInterface $logger): void
    {
        $logger->debug('Starting after build hook');

        $process = Process::run($this->afterBuildHook);
        if ($process->failed()) {
            $logger->error('After build hook failed', ['output' => $process->output()]);
            return;
        }

        $logger->debug('Finished after build hook', ['output' => $process->output()]);
    }
}
