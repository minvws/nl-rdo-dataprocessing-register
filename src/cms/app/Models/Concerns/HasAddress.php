<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasAddress
{
    /**
     * @return MorphOne<Address, $this>
     */
    final public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
