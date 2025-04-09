<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Config\Config;
use App\Import\Factory;
use App\Models\Contracts\SnapshotSource;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\SnapshotState;
use App\Services\Snapshot\SnapshotFactory;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Throwable;
use Webmozart\Assert\Assert;

use function array_key_exists;
use function collect;
use function in_array;
use function is_bool;
use function is_int;
use function is_iterable;
use function is_string;
use function sprintf;

class AbstractFactory
{
    protected function createRelations(Model $model, string $organisationId, string $relation, array $collection, Factory $factory): void
    {
        foreach ($collection as $data) {
            $avgProcessor = $factory->create($data, $organisationId);

            if ($avgProcessor !== null) {
                $model->$relation()->save($avgProcessor);
            }
        }
    }

    /**
     * @throws InvalidConfig
     * @throws Throwable
     */
    protected function createSnapshot(
        Model&SnapshotSource $snapshotSource,
        int $version,
        string $status,
        SnapshotFactory $snapshotFactory,
    ): void {
        $snapshotStateConversions = Config::array('import.value_converters.snapshot_state');
        if (!array_key_exists($status, $snapshotStateConversions)) {
            return;
        }
        $name = $snapshotStateConversions[$status];
        Assert::string($name);

        /** @var SnapshotState $snapshotState */
        $snapshotState = SnapshotState::make($name, $snapshotSource);
        $snapshot = $snapshotFactory->fromSnapshotSource($snapshotSource, $version, $snapshotState);

        foreach ($snapshot->relatedSnapshotSources as $relatedSnapshotSource) {
            if ($relatedSnapshotSource->snapshotSource->getLatestSnapshotWithState([Established::class]) !== null) {
                continue;
            }

            if ($snapshotState instanceof Established) {
                $snapshotFactory->fromSnapshotSource($relatedSnapshotSource->snapshotSource, null, $snapshotState);
                continue;
            }

            if (
                $snapshotState instanceof Approved
                && $relatedSnapshotSource->snapshotSource->getLatestSnapshotWithState([Approved::class]) === null
            ) {
                $snapshotFactory->fromSnapshotSource($relatedSnapshotSource->snapshotSource, null, $snapshotState);
                continue;
            }

            if (
                $snapshotState instanceof InReview
                && $relatedSnapshotSource->snapshotSource->getLatestSnapshotWithState([InReview::class]) === null
            ) {
                $snapshotFactory->fromSnapshotSource($relatedSnapshotSource->snapshotSource, null, $snapshotState);
            }
        }
    }

    protected function getDescriptionFromOptions(?array $options, string $optionValue): ?string
    {
        if ($options === null) {
            return null;
        }

        foreach ($options as $option) {
            if (Str::of($option)->startsWith($optionValue)) {
                return Str::of($option)->replaceFirst(sprintf('%s: ', $optionValue), '')->toString();
            }
        }

        return null;
    }

    protected function toBoolean(string|bool|null $input): bool
    {
        if (is_bool($input)) {
            return $input;
        }

        return in_array($input, Config::array('import.value_converters.boolean_true'), true);
    }

    protected function toCarbon(string $input, ?string $format = null): CarbonImmutable
    {
        if ($format !== null) {
            return $this->convertToCarbon($input, $format);
        }

        $expectedFormats = Config::array('import.date.expectedFormats');
        foreach ($expectedFormats as $expectedFormat) {
            Assert::string($expectedFormat);

            try {
                return $this->convertToCarbon($input, $expectedFormat);
            } catch (InvalidFormatException) {
                // pass, will try next expected format
            }
        }

        throw new InvalidFormatException(
            sprintf('Could not parse date %s using expected formats [%s]', $input, Arr::join($expectedFormats, ', ')),
        );
    }

    protected function skipState(string $state): bool
    {
        $statesToSkip = Config::array('import.states_to_skip_import');

        return in_array($state, $statesToSkip, true);
    }

    protected function toString(array|bool|int|string|null $input): string
    {
        if ($input === null) {
            return '';
        }

        if (is_iterable($input)) {
            return collect($input)->implode("\n");
        }

        return (string) $input;
    }

    protected function toStringOrNull(array|int|string|null $input): ?string
    {
        if ($input === null) {
            return null;
        }

        if (is_string($input) || is_int($input)) {
            return (string) $input;
        }

        return collect($input)->implode("\n");
    }

    protected function toArray(?array $input): array
    {
        if ($input === null) {
            return [];
        }

        return $input;
    }

    private function convertToCarbon(string $input, string $format): CarbonImmutable
    {
        $date = CarbonImmutable::createFromFormat(
            $format,
            $input,
            Config::string('import.date.timezone'),
        );
        Assert::isInstanceOf($date, CarbonImmutable::class);

        return $date->utc();
    }
}
