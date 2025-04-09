<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Contracts\SnapshotSource;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use ReflectionClass;
use Webmozart\Assert\Assert;

trait HasSnapshots
{
    public function getDisplayName(): string
    {
        $name = $this->getAttribute('name');
        Assert::string($name, 'name-attribute should be a string');

        return $name;
    }

    public function getDisplayNameOrDefaultValue(?string $name): string
    {
        if ($name === null) {
            $name = '—';
        }

        return $name;
    }

    /**
     * @param array<class-string<SnapshotState>> $snapshotStates
     */
    public function getLatestSnapshotWithState(array $snapshotStates): ?Snapshot
    {
        $snapshotStates = new Collection($snapshotStates);
        $states = $snapshotStates->map(static function (string $snapshotState): string {
            return $snapshotState::$name;
        });

        return $this->snapshots()
            ->whereIn('state', $states)
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * @return MorphMany<Snapshot, $this>
     */
    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'snapshot_source');
    }

    /**
     * @return Collection<class-string<SnapshotSource>, Collection<int, SnapshotSource>>
     */
    public function getRelatedSnapshotSources(): Collection
    {
        $relatedSnapshotSources = new Collection();

        $reflectionClass = new ReflectionClass($this);
        foreach ($reflectionClass->getMethods() as $method) {
            foreach ($method->getAttributes(RelatedSnapshotSource::class) as $attribute) {
                $relatedSnapshotSources->put($attribute->getArguments()[0], $this->{$method->name}()->get());
            }
        }

        return $relatedSnapshotSources;
    }
}
