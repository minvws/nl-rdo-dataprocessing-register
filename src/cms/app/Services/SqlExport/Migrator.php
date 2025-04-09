<?php

declare(strict_types=1);

namespace App\Services\SqlExport;

use Illuminate\Database\Migrations\Migrator as IlluminateMigrator;
use Illuminate\Support\Collection;

use function collect;
use function sprintf;

class Migrator extends IlluminateMigrator
{
    /**
     * @return Collection<array-key, non-falsy-string>
     */
    public function getMigratorQueries(string $migrationFile, string $method): Collection
    {
        $queries = $this->getQueries($this->resolvePath($migrationFile), $method);

        return collect($queries)
            ->map(static function ($query) {
                return sprintf('%s;', $query['query']);
            });
    }
}
