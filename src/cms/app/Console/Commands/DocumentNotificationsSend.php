<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Notification\DocumentNotificationService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class DocumentNotificationsSend extends Command
{
    protected $signature = 'document:notifications';
    protected $description = 'Send scheduled notifications';

    public function handle(DocumentNotificationService $documentNotificationService): int
    {
        $documentNotificationService->notifyAllWithDate(CarbonImmutable::today());

        $this->output->success('Notifications sent');

        return self::SUCCESS;
    }
}
