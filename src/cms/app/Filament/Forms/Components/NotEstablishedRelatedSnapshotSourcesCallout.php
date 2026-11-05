<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Models\Contracts\SnapshotSource;
use Closure;
use Filament\Schemas\Components\Callout;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;
use function class_basename;
use function e;
use function sprintf;

class NotEstablishedRelatedSnapshotSourcesCallout extends Callout
{
    /**
     * The snapshot sources closure receives the same injected parameters as other Filament closures.
     */
    public static function makeForSnapshotSources(Closure $snapshotSources): static
    {
        return parent::make(__('snapshot.related_snapshot_sources_not_established'))
            ->warning()
            ->description(static function (Callout $component) use ($snapshotSources): HtmlString {
                $lines = self::evaluateSnapshotSources($component, $snapshotSources)
                    ->groupBy(static function (SnapshotSource $snapshotSource): string {
                        return __(sprintf('%s.model_plural', Str::snake(class_basename($snapshotSource))));
                    })
                    ->map(static function (Collection $group, string $label): string {
                        $names = $group->map(static function (SnapshotSource $snapshotSource): string {
                            return e($snapshotSource->getDisplayName());
                        });

                        return sprintf('<strong>%s</strong><br>%s', e($label), $names->implode(', '));
                    });

                return new HtmlString($lines->implode('<br>'));
            })
            ->visible(static function (Callout $component) use ($snapshotSources): bool {
                return self::evaluateSnapshotSources($component, $snapshotSources)->isNotEmpty();
            });
    }

    /**
     * @return Collection<int, SnapshotSource>
     */
    private static function evaluateSnapshotSources(Callout $component, Closure $snapshotSources): Collection
    {
        $evaluated = $component->evaluate($snapshotSources);
        Assert::isInstanceOf($evaluated, Collection::class);

        $snapshotSourceList = $evaluated->values()->all();
        Assert::allIsInstanceOf($snapshotSourceList, SnapshotSource::class);

        return new Collection($snapshotSourceList);
    }
}
