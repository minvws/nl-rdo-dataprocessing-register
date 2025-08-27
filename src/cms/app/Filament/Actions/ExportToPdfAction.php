<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Enums\Snapshot\SnapshotDataSection;
use App\Models\Snapshot;
use App\Models\SnapshotData;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webmozart\Assert\Assert;

use function __;
use function response;
use function sprintf;

class ExportToPdfAction extends Action
{
    public static function make(?string $name = 'export_to_pdf'): static
    {
        return parent::make($name)
            ->label(__('snapshot.export_to_pdf'))
            ->visible(static function (Snapshot $record): bool {
                return $record->snapshotData !== null;
            })
            ->action(static function (Snapshot $record, SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer): StreamedResponse {
                return response()->streamDownload(static function () use ($record, $snapshotDataMarkdownRenderer): void {
                    Assert::isInstanceOf($record->snapshotData, SnapshotData::class);

                    $publicMarkdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
                        $record,
                        $record->snapshotData->public_markdown,
                        SnapshotDataSection::PUBLIC,
                    );
                    $privateMarkdown = $snapshotDataMarkdownRenderer->fromSnapshotMarkdown(
                        $record,
                        $record->snapshotData->private_markdown,
                        SnapshotDataSection::PRIVATE,
                    );

                    $html = Blade::render('export.pdf', [
                        'record' => $record,
                        'publicMarkdown' => $publicMarkdown,
                        'privateMarkdown' => $privateMarkdown,
                    ]);

                    echo Pdf::loadHTML($html)->stream();
                }, sprintf('%s-%s_.pdf', $record->name, $record->version));
            });
    }
}
