<?php

declare(strict_types=1);

namespace App\Services\Snapshot;

use App\Enums\MarkdownField;
use App\Models\Contracts\SnapshotSource;
use App\Models\RelatedSnapshotSource;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Models\States\Snapshot\Established;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function sprintf;

readonly class SnapshotDataMarkdownRenderer
{
    /**
     * @param array<string, string> $renderTemplates
     */
    public function __construct(
        private Factory $view,
        private array $renderTemplates,
    ) {
    }

    public function fromSnapshotData(SnapshotData $snapshotData, MarkdownField $field): string
    {
        $markdown = Str::of((string) $snapshotData->{$field->value});

        foreach ($this->renderTemplates as $model => $viewTemplate) {
            Assert::subclassOf($model, SnapshotSource::class);
            $templateTag = sprintf('<!--- #%s# --->', $model);

            if (!$markdown->contains($templateTag)) {
                continue;
            }

            $relatedSnapshots = $this->getRelatedSnapshots($snapshotData->snapshot, $model);
            $relatedSnapshotsMarkdown = $this->view->make($viewTemplate, ['snapshots' => $relatedSnapshots])->render();

            $markdown = $markdown->replace($templateTag, $relatedSnapshotsMarkdown);
        }

        return $markdown->markdown()->toString();
    }

    /**
     * @param class-string<SnapshotSource> $snapshotSource
     *
     * @return Collection<int, SnapshotSource>
     */
    private function getRelatedSnapshots(Snapshot $snapshot, string $snapshotSource): Collection
    {
        $relatedSnapshots = new Collection();

        $relatedSnapshotSources = $snapshot->relatedSnapshotSources
            ->where('snapshot_source_type', $snapshotSource);

        /** @var RelatedSnapshotSource $relatedSnapshotSource */
        foreach ($relatedSnapshotSources as $relatedSnapshotSource) {
            $relatedSnapshot = $relatedSnapshotSource->snapshotSource->getLatestSnapshotWithState([Established::class]);
            if ($relatedSnapshot === null) {
                continue;
            }

            $relatedSnapshots->push($relatedSnapshot);
        }

        return $relatedSnapshots;
    }
}
