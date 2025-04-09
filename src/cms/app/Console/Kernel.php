<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\DeleteNonOrganisationUsers;
use App\Console\Commands\DocumentNotificationsSend;
use App\Console\Commands\PruneExpiredUserLoginTokens;
use App\Console\Commands\PublicWebsiteRefresh;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use function base_path;
use function sprintf;

class Kernel extends ConsoleKernel
{
    protected function commands(): void
    {
        $this->load(sprintf('%s/Commands', __DIR__));

        require_once base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule): void
    {
        // every fifteen minutes
        $schedule->command(PruneExpiredUserLoginTokens::class)
            ->everyFifteenMinutes();

        // daily
        $schedule->command(PublicWebsiteRefresh::class, ['-D'])
            ->dailyAt('01:00');
        $schedule->command(DocumentNotificationsSend::class)
            ->dailyAt('09:00');
        $schedule->command(DeleteNonOrganisationUsers::class)
            ->daily();
    }
}
