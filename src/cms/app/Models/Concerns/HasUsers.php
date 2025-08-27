<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\UserCollection;
use App\Models\Contracts\Cloneable;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read UserCollection $users
 */
trait HasUsers
{
    final public function initializeHasUsers(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['users']);
        }
    }

    /**
     * @return MorphToMany<User, $this>
     */
    final public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'user_relatable')
            ->withTimestamps();
    }
}
