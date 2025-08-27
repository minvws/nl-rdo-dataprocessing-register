<?php

declare(strict_types=1);

namespace App\Services\SqlExport;

use Illuminate\Database\Migrations\Migrator as IlluminateMigrator;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

use function collect;
use function sprintf;

class Migrator extends IlluminateMigrator
{
    /**
     * @return Collection<array-key, non-falsy-string>
     */
    public function getMigratorQueries(string $migrationFile, string $method): Collection
    {
        $migrationQueries = $this->getQueries($this->resolvePath($migrationFile), $method);

        return collect($migrationQueries)
            ->map(static function ($migratorQuery): string {
                Assert::isArray($migratorQuery);
                Assert::keyExists($migratorQuery, 'query');

                $query = $migratorQuery['query'];
                Assert::string($query);

                return sprintf('%s;', $query);
            });
    }
}
