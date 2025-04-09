<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\UserLoginToken;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Webmozart\Assert\Assert;

use function sprintf;

class PruneExpiredUserLoginTokens extends Command
{
    protected $signature = 'app:prune-expired-user-login-tokens';
    protected $description = 'Delete expired user login tokens.';

    public function handle(): void
    {
        $this->info('Pruning expired user login tokens...');

        $count = UserLoginToken::where('expires_at', '<', CarbonImmutable::now())->delete();
        Assert::integer($count);

        $this->info(sprintf('%s expired user login tokens pruned.', $count));
    }
}
