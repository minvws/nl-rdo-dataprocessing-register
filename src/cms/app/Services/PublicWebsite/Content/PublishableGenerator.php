<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite\Content;

use App\Enums\Snapshot\SnapshotDataSection;
use App\Models\Contracts\Publishable;
use App\Models\States\Snapshot\Established;
use App\Services\PublicWebsite\Generator;
use App\Services\PublicWebsite\PathGenerator;
use App\Services\PublicWebsite\PublicWebsiteFilesystem;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use Illuminate\Contracts\View\Factory;

use function array_merge;

class PublishableGenerator extends Generator
{
    public function __construct(
        private readonly PathGenerator $pathGenerator,
        private readonly PublicWebsiteFilesystem $publicWebsiteFilesystem,
        private readonly SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer,
        private readonly Factory $viewFactory,
    ) {
    }

    public function generate(Publishable $publishable): void
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

        $content = $this->viewFactory->make('public-website.processing-record', [
            'frontmatter' => $this->convertToFrontmatter($frontmatterData),
            'markdown' => $this->snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
                $snapshot,
                $snapshotData->public_markdown,
                SnapshotDataSection::PUBLIC,
            ),
        ])->render();

        $this->publicWebsiteFilesystem->write($this->pathGenerator->getPublishablePath($publishable), $content);
    }
}
