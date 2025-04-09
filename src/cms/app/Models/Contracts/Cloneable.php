<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Cloneable
{
    /**
     * @param array<string> $except
     */
    public function clone(array $except = []): Model;
}
