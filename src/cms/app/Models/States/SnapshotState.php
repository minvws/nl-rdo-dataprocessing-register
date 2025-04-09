<?php

declare(strict_types=1);

namespace App\Models\States;

use App\Enums\Authorization\Permission;
use App\Filament\Actions\SnapshotTransition\SnapshotTransitionAction;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;
use App\Models\States\Snapshot\Obsolete;
use Livewire\Wireable;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Webmozart\Assert\Assert;

abstract class SnapshotState extends State implements Wireable
{
    public const DEFAULT_STATE = InReview::class;
    public const OBSOLETE_STATE = Obsolete::class;

    // options: `gray`, `danger`, `info`, `primary`, `success` and `warning`
    public static string $color = 'gray';
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

        $config->allowTransition(InReview::class, Approved::class);
        $config->allowTransition(Approved::class, Established::class);
        $config->allowTransition(InReview::class, Obsolete::class);
        $config->allowTransition(Approved::class, Obsolete::class);
        $config->allowTransition(Established::class, Obsolete::class);

        return $config;
    }

    public function toLivewire(): array
    {
        return [
            'name' => static::$name,
            'color' => static::$color,
        ];
    }

    public static function fromLivewire(mixed $value): string
    {
        Assert::string($value);

        return $value;
    }
}
