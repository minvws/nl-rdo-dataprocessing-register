<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\FgRemark;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read FgRemark $fgRemark
 */
trait HasFgRemark
{
    /**
     * @return MorphOne<FgRemark, $this>
     */
    final public function fgRemark(): MorphOne
    {
        return $this->morphOne(FgRemark::class, 'fg_remark_relatable');
    }
}
