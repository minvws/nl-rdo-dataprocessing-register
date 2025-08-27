<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite\Content;

use App\Enums\Snapshot\SnapshotDataSection;
use App\Models\Contracts\Publishable;
use App\Models\States\Snapshot\Established;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use App\Services\StaticWebsite\Generator;
use App\Services\StaticWebsite\PathGenerator;
use App\Services\StaticWebsite\StaticWebsiteFilesystem;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function array_merge;

class PublishableGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly StaticWebsiteFilesystem $staticWebsiteFilesystem,
        private readonly SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer,
        private readonly Factory $viewFactory,
    ) {
    }

    /**
     * @param array<string> $parentSlugs
     */
    public function generate(Publishable $publishable, array $parentSlugs): void
    {
        $snapshot = $publishable->getLatestSnapshotWithState([Established::class]);
        if ($snapshot === null) {
            return;
        }

        $snapshotData = $snapshot->snapshotData;
        if ($snapshotData === null) {
            return;
        }

        $frontmatterData = array_merge(
            ['id' => $snapshot->id->toString()],
            $snapshotData->public_frontmatter,
        );


        $content = $this->viewFactory->make('static-website.processing-record', [
            'frontmatter' => $this->convertToFrontmatter($frontmatterData),
            'markdown' => $this->snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
                $snapshot,
                $snapshotData->public_markdown,
                SnapshotDataSection::PUBLIC,
            ),
        ])->render();

        $path = Arr::join(array_merge($parentSlugs, [$publishable->getOrganisation()->publicWebsiteTree?->slug]), '/');
        $publishablePath = $this->pathGenerator->getPublishablePath($path, Str::slug($publishable->getPublicIdentifier()));
        $this->staticWebsiteFilesystem->write($publishablePath, $content);
    }
}
