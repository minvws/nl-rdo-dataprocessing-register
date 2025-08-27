<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Notification\SnapshotApprovalSignRequestMailer;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class SnapshotApprovalBatchNotifications extends Command
{
    protected $signature = 'app:snapshot-approval-batch-notifications';
    protected $description = 'Send snapshot-approval batch notifications';

    public function handle(
        LoggerInterface $logger,
        SnapshotApprovalSignRequestMailer $snapshotApprovalSignRequestMailer,
    ): int {
        $logger->info('sending weekly snapshot-approval batches');

        $snapshotApprovalSignRequestMailer->handleWeekly();

        $this->output->success('done');

        return self::SUCCESS;
    }
}
