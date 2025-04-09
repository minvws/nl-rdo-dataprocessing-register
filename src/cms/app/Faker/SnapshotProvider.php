<?php

declare(strict_types=1);

namespace App\Faker;

use App\Models\States\Snapshot\Obsolete;
use App\Models\States\SnapshotState;
use Closure;
use Faker\Provider\Miscellaneous;
use Webmozart\Assert\Assert;

use function array_key_exists;
use function in_array;
use function sprintf;

class SnapshotProvider extends Miscellaneous
{
    /** @var array<string, array<class-string<SnapshotState>>> */
    private array $groups = [];

    /**
     * @param array<class-string<SnapshotState>> $excluded
     */
    public function snapshotState(array $excluded = []): Closure
    {
        return function (array $attributes) use ($excluded): string {
            /** @var class-string<SnapshotState> $state */
            $state = SnapshotState::all()
                ->reject(static function (string $state) use ($excluded) {
                    if ($state === Obsolete::class) {
                        return false;
                    }

                    return in_array($state, $excluded, true);
                })
                ->random();

            if ($state === SnapshotState::OBSOLETE_STATE) {
                return $state;
            }

            Assert::string($attributes['snapshot_source_type']);
            Assert::string($attributes['snapshot_source_id']);

            $group = sprintf('%s-%s', $attributes['snapshot_source_type'], $attributes['snapshot_source_id']);
            if (array_key_exists($group, $this->groups) && in_array($state, $this->groups[$group], true)) {
                return SnapshotState::OBSOLETE_STATE;
            }

            $this->groups[$group][] = $state;

            return $state;
        };
    }
}
