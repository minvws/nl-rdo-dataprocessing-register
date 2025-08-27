<?php

declare(strict_types=1);

namespace App\Models\States;

use App\Enums\Authorization\Permission;
use App\Enums\StateColor;
use App\Filament\Actions\SnapshotTransition\SnapshotTransitionAction;
use App\Models\Snapshot;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use App\Models\States\Transitions\ApprovedTransition;
use App\Models\States\Transitions\EstablishedTransition;
use App\Models\States\Transitions\ObsoleteTransition;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

/**
 * @extends State<Snapshot>
 */
abstract class SnapshotState extends State
{
    public const DEFAULT_STATE = InReview::class;
    public const OBSOLETE_STATE = Obsolete::class;

    public static StateColor $color = StateColor::GRAY;
    public static string $name = 'none';
    public static Permission $requiredPermission = Permission::SNAPSHOT_CREATE;

    /**
     * @return class-string<SnapshotTransitionAction>
     */
    abstract public static function getAction(): string;

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        $config = parent::config()
            ->default(self::DEFAULT_STATE)
            ->registerState(InReview::class)
            ->registerState(Approved::class)
            ->registerState(Established::class)
            ->registerState(Obsolete::class);

        $config->ignoreSameState();
        $config->allowTransition(InReview::class, Approved::class, ApprovedTransition::class);
        $config->allowTransition(Approved::class, Established::class, EstablishedTransition::class);
        $config->allowTransition(InReview::class, Obsolete::class, ObsoleteTransition::class);
        $config->allowTransition(Approved::class, Obsolete::class, ObsoleteTransition::class);
        $config->allowTransition(Established::class, Obsolete::class, ObsoleteTransition::class);

        return $config;
    }
}
