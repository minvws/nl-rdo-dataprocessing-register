<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByCreatedAtAscScope implements Scope
{
    public function apply(Builder $builder, Model $model): Builder
    {
        return $builder->orderBy('created_at');
    }
}
