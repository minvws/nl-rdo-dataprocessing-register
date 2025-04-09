<?php

declare(strict_types=1);

namespace App\Filament\Resources\SnapshotResource\Pages;

use App\Filament\Actions\ExportToPdfAction;
use App\Filament\Infolists\Tabs\Snapshot\ViewApprovalTab;
use App\Filament\Infolists\Tabs\Snapshot\ViewHistoryTab;
use App\Filament\Infolists\Tabs\Snapshot\ViewInfoTab;
use App\Filament\Resources\SnapshotResource;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Webmozart\Assert\Assert;

use function __;

class ViewSnapshot extends ViewRecord
{
    public const TAB_ID_APPROVAL = 'approval';
    public const TAB_ID_HISTORY = 'history';
    public const TAB_ID_INFO = 'info';
    public const REFRESH_LIVEWIRE_COMPONENT = 'refresh-view-snapshot-event';

    protected static string $resource = SnapshotResource::class;

    public function getBreadcrumbs(): array
    {
        $snapshot = $this->record;
        Assert::isInstanceOf($snapshot, Snapshot::class);
        $snapshoutSource = $snapshot->snapshotSource;

        /** @var class-string<Resource> $resource */
        $resource = Filament::getModelResource($snapshoutSource);
        $resourceUrl = $resource::getGlobalSearchResultUrl($snapshoutSource);

        return [
            $resourceUrl => __('snapshot.back_to', ['resource' => $resource::getModelLabel()]),
        ];
    }

    /**
     * @throws InvalidConfig
     */
    protected function getHeaderActions(): array
    {
        return [
            ExportToPdfAction::make(),
            ...$this->getSnapshotWorkflowActions(),
        ];
    }

    #[On(self::REFRESH_LIVEWIRE_COMPONENT)]
    public function render(): View
    {
        return parent::render();
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make()
                    ->tabs([
                        ViewInfoTab::make(__('snapshot.tab_info'))
                            ->id(self::TAB_ID_INFO),
                        ViewApprovalTab::make(__('snapshot.tab_approval'))
                            ->id(self::TAB_ID_APPROVAL),
                        ViewHistoryTab::make(__('snapshot.tab_history'))
                            ->id(self::TAB_ID_HISTORY),
                    ])
                    ->contained(false)
                    ->persistTabInQueryString(),
            ])
            ->columns(1);
    }

    /**
     * @return array<Action>
     *
     * @throws InvalidConfig
     */
    private function getSnapshotWorkflowActions(): array
    {
        /** @var Snapshot $snapshot */
        $snapshot = $this->record;
        /** @var array<int, string> $transitionableStates */
        $transitionableStates = $snapshot->state->transitionableStates();

        $actions = [];
        foreach ($transitionableStates as $transitionableState) {
            /** @var SnapshotState $snapshotState */
            $snapshotState = SnapshotState::make($transitionableState, $snapshot);
            $action = $snapshotState::getAction();

            $actions[] = $action::makeForSnapshotState($snapshot, $snapshotState);
        }

        return $actions;
    }
}
