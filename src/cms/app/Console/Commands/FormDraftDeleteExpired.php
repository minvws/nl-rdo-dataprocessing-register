<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Config\Config;
use App\Models\FormDraft;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Webmozart\Assert\Assert;

use function sprintf;

class FormDraftDeleteExpired extends Command
{
    protected $signature = 'form-draft:delete-expired';
    protected $description = 'Delete expired form drafts.';

    public function handle(): void
    {
        $this->info('Deleting expired form drafts...');

        $retentionDays = Config::integer('autosave.retention_days');

        $count = FormDraft::where('updated_at', '<', CarbonImmutable::now()->subDays($retentionDays))->delete();
        Assert::integer($count);

        $this->info(sprintf('%s expired form drafts deleted.', $count));
    }
}
