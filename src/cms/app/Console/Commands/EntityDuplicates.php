<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Collections\OrganisationCollection;
use App\Models\Organisation;
use App\Models\Processor;
use App\Models\System;
use App\Services\NameSimilarityService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

use function array_map;
use function count;
use function implode;
use function in_array;
use function is_numeric;
use function is_string;
use function sprintf;
use function usort;

/**
 * @phpstan-type Item array{name: string, id: string, created_at: string, extra: list<string>}
 */
class EntityDuplicates extends Command
{
    private const ENTITIES = ['processor', 'system'];
    private const TABLE_HEADERS = [
        'Similarity',
        'Name A',
        'Name B',
        'Id A',
        'Id B',
        'Created at A',
        'Created at B',
    ];
    private const EXTRA_TABLE_HEADERS = [
        'processor' => ['Email A', 'Email B', 'Phone A', 'Phone B'],
        'system' => [],
    ];

    protected $signature = 'app:entity-duplicates
        {entity : the entity to check: processor or system (systems and applications)}
        {--organisation= : filter on organisation slug or (partial) name, default is all organisations}
        {--similarity=80 : minimum name similarity percentage (0-100), 100 is identical}';
    protected $description = 'List entities with a duplicate or similar name, grouped per organisation';

    public function handle(): int
    {
        $entity = $this->argument('entity');
        if (!in_array($entity, self::ENTITIES, true)) {
            $this->error(sprintf('entity must be one of: %s', implode(', ', self::ENTITIES)));

            return self::FAILURE;
        }

        $minimumSimilarity = $this->getMinimumSimilarity();
        if ($minimumSimilarity === null) {
            $this->error('similarity must be a number between 0 and 100');

            return self::FAILURE;
        }

        $organisationFilter = $this->option('organisation');
        $organisations = $this->getOrganisations($organisationFilter);
        if ($organisationFilter !== null && $organisations->isEmpty()) {
            $this->error(sprintf('no organisation found for: %s', $organisationFilter));

            return self::FAILURE;
        }

        $hasDuplicates = false;
        foreach ($organisations as $organisation) {
            $matches = $this->findDuplicates($this->getItems($organisation, $entity), $minimumSimilarity);
            if ($matches === []) {
                continue;
            }

            $hasDuplicates = true;

            $this->output->info(sprintf('%s (%s)', $organisation->name, $organisation->slug));
            $this->table([...self::TABLE_HEADERS, ...self::EXTRA_TABLE_HEADERS[$entity]], $this->toRows($matches));
        }

        if (!$hasDuplicates) {
            $this->output->info(sprintf('no duplicate %ss found', $entity));
        }

        return self::SUCCESS;
    }

    /**
     * @return list<Item>
     */
    private function getItems(Organisation $organisation, string $entity): array
    {
        $entities = $entity === 'processor' ? $organisation->processors() : $organisation->systems();

        return $entities
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(self::toItem(...))
            ->values()
            ->all();
    }

    /**
     * @return Item
     */
    private static function toItem(Processor|System $model): array
    {
        return [
            'name' => $model instanceof Processor ? $model->name : (string) $model->description,
            'id' => $model->id->toString(),
            'created_at' => $model->created_at->toDateTimeString(),
            'extra' => $model instanceof Processor ? [$model->email, $model->phone] : [],
        ];
    }

    private function getMinimumSimilarity(): ?float
    {
        $similarity = $this->option('similarity');
        if (!is_string($similarity) || !is_numeric($similarity)) {
            return null;
        }

        $similarity = (float) $similarity;
        if ($similarity < 0.0 || $similarity > 100.0) {
            return null;
        }

        return $similarity;
    }

    private function getOrganisations(?string $filter): OrganisationCollection
    {
        $organisationQuery = Organisation::query()
            ->orderBy('name');

        if ($filter !== null) {
            $organisationQuery->where(static function (Builder $query) use ($filter): void {
                $query->where('slug', $filter)
                    ->orWhereLike('name', sprintf('%%%s%%', $filter))
                    ->orWhereLike('slug', sprintf('%%%s%%', $filter));
            });
        }

        /** @var OrganisationCollection $organisations */
        $organisations = $organisationQuery->get();

        return $organisations;
    }

    /**
     * @param list<Item> $items
     *
     * @return list<array{float, Item, Item}>
     */
    private function findDuplicates(array $items, float $minimumSimilarity): array
    {
        $itemCount = count($items);

        $names = array_map(NameSimilarityService::normalize(...), array_map(static function (array $item): string {
            return $item['name'];
        }, $items));

        $matches = [];
        for ($first = 0; $first < $itemCount; $first++) {
            for ($second = $first + 1; $second < $itemCount; $second++) {
                $similarity = NameSimilarityService::compare($names[$first], $names[$second], $minimumSimilarity);
                if ($similarity === null) {
                    continue;
                }

                $matches[] = [$similarity, $items[$first], $items[$second]];
            }
        }

        usort($matches, static function (array $first, array $second): int {
            return [$second[0], $first[1]['name'], $first[2]['name']]
                <=> [$first[0], $second[1]['name'], $second[2]['name']];
        });

        return $matches;
    }

    /**
     * @param list<array{float, Item, Item}> $matches
     *
     * @return list<list<string>>
     */
    private function toRows(array $matches): array
    {
        $rows = [];
        foreach ($matches as [$similarity, $first, $second]) {
            $row = [
                sprintf('%.1f%%', $similarity),
                $first['name'],
                $second['name'],
                $first['id'],
                $second['id'],
                $first['created_at'],
                $second['created_at'],
            ];
            foreach ($first['extra'] as $index => $value) {
                $row[] = $value;
                $row[] = $second['extra'][$index];
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
