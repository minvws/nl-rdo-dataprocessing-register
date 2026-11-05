<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @deprecated use app:entity-duplicates processor
 */
class ProcessorDuplicates extends Command
{
    protected $signature = 'app:processor-duplicates
        {--organisation= : filter on organisation slug or (partial) name, default is all organisations}
        {--similarity=80 : minimum name similarity percentage (0-100), 100 is identical}';
    protected $description = 'Deprecated, use app:entity-duplicates processor';

    public function handle(): int
    {
        $this->warn('app:processor-duplicates is deprecated, use app:entity-duplicates processor');

        return $this->call('app:entity-duplicates', [
            'entity' => 'processor',
            '--organisation' => $this->option('organisation'),
            '--similarity' => $this->option('similarity'),
        ]);
    }
}
