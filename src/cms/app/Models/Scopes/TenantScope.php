<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Facades\Authentication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        return $builder->whereBelongsTo(Authentication::organisation());
    }
}
