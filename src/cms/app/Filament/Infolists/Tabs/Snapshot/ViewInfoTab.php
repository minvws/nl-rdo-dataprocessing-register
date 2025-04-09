<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Tabs\Snapshot;

use App\Enums\Authorization\Permission;
use App\Enums\MarkdownField;
use App\Enums\Snapshot\SnapshotApprovalStatus;
use App\Facades\Authentication;
use App\Facades\Authorization;
use App\Facades\DateFormat;
use App\Filament\Infolists\Components\SnapshotStateEntry;
use App\Filament\Infolists\Components\SnapshotUrlEntry;
use App\Filament\Resources\SnapshotResource\Pages\ViewSnapshot;
use App\Models\Snapshot;
use App\Models\SnapshotApproval;
use App\Services\Snapshot\SnapshotApprovalService;
use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Webmozart\Assert\Assert;

use function __;
use function class_basename;
use function sprintf;

class ViewInfoTab extends Tab
{
    public static function make(string $label): static
    {
        return parent::make($label)
            ->icon('heroicon-o-information-circle')
            ->schema([
                self::getPropertiesSection(),
                self::getPublicDataSection(),
                self::getPrivateDataSection(),
                self::getRelatedSnapshotSourcesSection(),
                self::getApprovalSection(),
            ]);
    }

    private static function getPropertiesSection(): Section
    {
        return Section::make(__('snapshot.properties'))
            ->schema([
                TextEntry::make('snapshot_source_type')
                    ->label(__('snapshot.snapshot_source_type'))
                    ->formatStateUsing(static function (string $state): string {
                        return __(sprintf('%s.model_singular', Str::snake(class_basename($state))));
                    }),
                TextEntry::make('snapshotSource')
                    ->label(__('snapshot.snapshot_source_display_name'))
                    ->formatStateUsing(static function (Snapshot $snapshot): string {
                        return $snapshot->snapshotSource->getDisplayName();
                    }),
                TextEntry::make('version')
                    ->label(__('snapshot.version')),
                TextEntry::make('name')
                    ->label(__('snapshot.name')),
                SnapshotStateEntry::make(),
                SnapshotUrlEntry::make(),
            ])
            ->columns(2);
    }

    private static function getPublicDataSection(): Section
    {
        return Section::make(__('snapshot.public_data'))
            ->description(new HtmlString(sprintf(
                '<span class="text-primary-600 dark:text-primary-400"><strong>%s:</strong> %s</span>',
                __('general.attention'),
                __('snapshot.markdown_replacement_help'),
            )))
            ->schema([
                TextEntry::make('snapshotData.public_markdown')
                    ->label('')
                    ->formatStateUsing(
                        static function (Snapshot $snapshot, SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer): string {
                            Assert::notNull($snapshot->snapshotData);

                            return $snapshotDataMarkdownRenderer->fromSnapshotData(
                                $snapshot->snapshotData,
                                MarkdownField::PUBLIC_MARKDOWN,
                            );
                        },
                    )
                    ->columnSpan(2)
                    ->markdown(),
            ]);
    }

    private static function getPrivateDataSection(): Section
    {
        return Section::make(__('snapshot.private_data'))
            ->description(new HtmlString(sprintf(
                '<span class="text-primary-600 dark:text-primary-400"><strong>%s:</strong> %s</span>',
                __('general.attention'),
                __('snapshot.markdown_replacement_help'),
            )))
            ->schema([
                TextEntry::make('snapshotData.private_markdown')
                    ->label('')
                    ->formatStateUsing(
                        static function (Snapshot $snapshot, SnapshotDataMarkdownRenderer $snapshotDataMarkdownRenderer): string {
                            Assert::notNull($snapshot->snapshotData);

                            return $snapshotDataMarkdownRenderer->fromSnapshotData(
                                $snapshot->snapshotData,
                                MarkdownField::PRIVATE_MARKDOWN,
                            );
                        },
                    )
                    ->columnSpan(2)
                    ->markdown(),
            ]);
    }

    private static function getRelatedSnapshotSourcesSection(): Section
    {
        return Section::make(__('snapshot.related_snapshot_sources'))
            ->view('filament.infolists.components.entries.snapshot_related_snapshot_sources')
            ->visible(static function (Snapshot $snapshot): bool {
                return $snapshot->relatedSnapshotSources()->exists();
            });
    }

    private static function getApprovalSection(): Section
    {
        return Section::make(__('snapshot_approval.personal'))
            ->schema([
                self::getApprovalInfo(),
                self::getApprovalButtons(),
            ])
            ->visible(Authorization::hasPermission(Permission::SNAPSHOT_APPROVAL_UPDATE_PERSONAL));
    }

    private static function getApprovalInfo(): Group
    {
        return Group::make([
            TextEntry::make('snapshotApprovals')
                ->label(__('snapshot_approval.status'))
                ->formatStateUsing(static function (Snapshot $snapshot): string {
                    /** @var SnapshotApproval $snapshotApproval */
                    $snapshotApproval = $snapshot->snapshotApprovals()
                        ->where('assigned_to', Authentication::user()->id)
                        ->firstOrFail();

                    return __(sprintf('snapshot_approval_status.%s', $snapshotApproval->status->value));
                }),
            TextEntry::make('snapshotApprovals.updated_at')
                ->label(__('snapshot_approval.reviewed_at'))
                ->formatStateUsing(static function (Snapshot $snapshot) {
                    /** @var SnapshotApproval $snapstotApproval */
                    $snapstotApproval = $snapshot->snapshotApprovals()
                        ->where('assigned_to', Authentication::user()->id)
                        ->firstOrFail();

                    return DateFormat::toDateTime($snapstotApproval->updated_at);
                }),
        ])
            ->visible(static function (Snapshot $snapshot): bool {
                /** @var SnapshotApproval|null $snapshotApproval */
                $snapshotApproval = $snapshot->snapshotApprovals()
                    ->where('assigned_to', Authentication::user()->id)
                    ->first();

                if ($snapshotApproval === null) {
                    return false;
                }

                return $snapshotApproval->status !== SnapshotApprovalStatus::UNKNOWN;
            })
            ->columns();
    }

    private static function getApprovalButtons(): ViewEntry
    {
        return ViewEntry::make('snapshot_approval_actions')
            ->view('filament.infolists.components.entries.snapshot_approval_actions')
            ->registerActions([
                InfolistAction::make('snapshot_approval_approve_action')
                    ->label(__('snapshot_approval.approve'))
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->action(static function (Snapshot $snapshot, SnapshotApprovalService $snapshotApprovalService): void {
                        /** @var SnapshotApproval $snapshotApproval */
                        $snapshotApproval = $snapshot->snapshotApprovals()
                            ->firstOrCreate([
                                'assigned_to' => Authentication::user()->id,
                            ]);
                        $snapshotApprovalService->setStatus(
                            Authentication::user(),
                            $snapshotApproval,
                            SnapshotApprovalStatus::APPROVED,
                        );
                    })
                    ->after(static function (Component $livewire): void {
                        $livewire->dispatch(ViewSnapshot::REFRESH_LIVEWIRE_COMPONENT);
                    })
                    ->requiresConfirmation()
                    ->disabled(static function (Snapshot $snapshot): bool {
                        /** @var SnapshotApproval|null $approval */
                        $approval = $snapshot->snapshotApprovals()
                            ->where('assigned_to', Authentication::user()->id)
                            ->first();

                        return $approval?->status === SnapshotApprovalStatus::APPROVED;
                    }),
                InfolistAction::make('snapshot_approval_decline_action')
                    ->label(__('snapshot_approval.decline'))
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(
                        static function (array $data, Snapshot $snapshot, SnapshotApprovalService $snapshotApprovalService): void {
                            /** @var SnapshotApproval $snapshotApproval */
                            $snapshotApproval = $snapshot->snapshotApprovals()
                                ->firstOrCreate([
                                    'assigned_to' => Authentication::user()->id,
                                ]);

                            $notes = $data['notes'];
                            Assert::nullOrString($notes);

                            $snapshotApprovalService->setStatus(
                                Authentication::user(),
                                $snapshotApproval,
                                SnapshotApprovalStatus::DECLINED,
                                $notes,
                            );
                        },
                    )
                    ->after(static function (Component $livewire): void {
                        $livewire->dispatch(ViewSnapshot::REFRESH_LIVEWIRE_COMPONENT);
                    })
                    ->form([
                        Textarea::make('notes'),
                    ])
                    ->requiresConfirmation()
                    ->disabled(static function (Snapshot $snapshot): bool {
                        /** @var SnapshotApproval|null $approval */
                        $approval = $snapshot->snapshotApprovals()
                            ->where('assigned_to', Authentication::user()->id)
                            ->first();

                        return $approval?->status === SnapshotApprovalStatus::DECLINED;
                    }),
            ]);
    }
}
