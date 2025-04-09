<?php

declare(strict_types=1);

namespace App\Filament\Actions\SnapshotTransition;

use App\Facades\Authorization;
use App\Models\Snapshot;
use App\Models\States\SnapshotState;
use App\Services\Snapshot\SnapshotStateTransitionService;
use Filament\Actions\Action;

use function __;
use function sprintf;

abstract class SnapshotTransitionAction extends Action
{
    public static function makeForSnapshotState(Snapshot $snapshot, SnapshotState $snapshotState): static
    {
        return parent::make(sprintf('snapshot_transition_to_%s', $snapshotState::$name))
            ->color($snapshotState::$color)
            ->label(__(sprintf('snapshot_state.transition.%s', $snapshotState::$name)))
            ->visible(Authorization::hasPermission($snapshotState::$requiredPermission))
            ->action(static function (SnapshotStateTransitionService $snapshotStateTransitionService) use (
                $snapshot,
                $snapshotState,
            ): void {
                $snapshotStateTransitionService->transitionToSnapshotState($snapshot, $snapshotState);
            })
            ->requiresConfirmation();
    }
}
