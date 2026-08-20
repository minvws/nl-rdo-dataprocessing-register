<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Collections\OrganisationCollection;
use App\Collections\ProcessorCollection;
use App\Models\Organisation;
use App\Models\Processor;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function count;
use function floor;
use function is_numeric;
use function is_string;
use function mb_strtolower;
use function min;
use function preg_replace;
use function similar_text;
use function sprintf;
use function strcmp;
use function strlen;
use function usort;

class ProcessorDuplicates extends Command
{
    private const TABLE_HEADERS = [
        'Similarity',
        'Name A',
        'Name B',
        'Id A',
        'Id B',
        'Created at A',
        'Created at B',
        'Email A',
        'Email B',
        'Phone A',
        'Phone B',
    ];

    protected $signature = 'app:processor-duplicates
        {--organisation= : filter on organisation slug or (partial) name, default is all organisations}
        {--similarity=80 : minimum name similarity percentage (0-100), 100 is identical}';
    protected $description = 'List processors with a duplicate or similar name, grouped per organisation';

    public function handle(): int
    {
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
            /** @var ProcessorCollection $processors */
            $processors = $organisation->processors()
                ->orderBy('created_at')
                ->orderBy('id')
                ->get(['id', 'organisation_id', 'name', 'email', 'phone', 'created_at']);

            $matches = $this->findDuplicates($processors, $minimumSimilarity);
            if ($matches === []) {
                continue;
            }

            $hasDuplicates = true;

            $this->output->info(sprintf('%s (%s)', $organisation->name, $organisation->slug));
            $this->table(self::TABLE_HEADERS, $this->toRows($matches));
        }

        if (!$hasDuplicates) {
            $this->output->info('no duplicate processors found');
        }

        return self::SUCCESS;
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
     * @return list<array{float, Processor, Processor}>
     */
    private function findDuplicates(ProcessorCollection $processors, float $minimumSimilarity): array
    {
        /** @var list<Processor> $items */
        $items = $processors->values()->all();
        $itemCount = count($items);

        $names = [];
        foreach ($items as $index => $processor) {
            $names[$index] = self::normalizeName($processor->name);
        }

        $matches = [];
        for ($first = 0; $first < $itemCount; $first++) {
            for ($second = $first + 1; $second < $itemCount; $second++) {
                $similarity = self::compare($names[$first], $names[$second], $minimumSimilarity);
                if ($similarity === null) {
                    continue;
                }

                $matches[] = [$similarity, $items[$first], $items[$second]];
            }
        }

        usort($matches, static function (array $first, array $second): int {
            return [$second[0], $first[1]->name, $first[2]->name]
                <=> [$first[0], $second[1]->name, $second[2]->name];
        });

        return $matches;
    }

    /**
     * Names are reduced to letters and digits only, so "Acme B.V.", "acme bv" and "Acme-BV" are all identical.
     * Legal forms are deliberately kept: "Acme B.V." and "Acme N.V." are different legal entities.
     */
    private static function normalizeName(string $name): string
    {
        $normalized = mb_strtolower(Str::ascii($name));
        $normalized = preg_replace('/[^a-z0-9]+/', '', $normalized);
        Assert::string($normalized);

        return $normalized;
    }

    /**
     * Returns the similarity percentage, or null when the names are not similar enough.
     */
    private static function compare(string $first, string $second, float $minimumSimilarity): ?float
    {
        if ($first === '' || $second === '') {
            return null;
        }

        $firstLength = strlen($first);
        $secondLength = strlen($second);

        // similar_text can never score higher than this, so skip the expensive comparison altogether
        $upperBound = 200 * min($firstLength, $secondLength) / ($firstLength + $secondLength);
        if ($upperBound < $minimumSimilarity) {
            return null;
        }

        $similarity = self::calculateSimilarity($first, $second);
        if ($similarity < $minimumSimilarity) {
            return null;
        }

        return $similarity;
    }

    private static function calculateSimilarity(string $first, string $second): float
    {
        if ($first === $second) {
            return 100.0;
        }

        // similar_text is not symmetric, a fixed argument order keeps the result independent of the record order
        if (strcmp($first, $second) > 0) {
            [$first, $second] = [$second, $first];
        }

        similar_text($first, $second, $percentage);

        // floor instead of round, so 100.0 is only reported for names that are identical after normalisation
        return floor($percentage * 10) / 10;
    }

    /**
     * @param list<array{float, Processor, Processor}> $matches
     *
     * @return list<list<string>>
     */
    private function toRows(array $matches): array
    {
        $rows = [];
        foreach ($matches as [$similarity, $first, $second]) {
            $rows[] = [
                sprintf('%.1f%%', $similarity),
                $first->name,
                $second->name,
                $first->id->toString(),
                $second->id->toString(),
                $first->created_at->toDateTimeString(),
                $second->created_at->toDateTimeString(),
                $first->email,
                $second->email,
                $first->phone,
                $second->phone,
            ];
        }

        return $rows;
    }
}
