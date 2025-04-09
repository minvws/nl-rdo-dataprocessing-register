<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Wpg\WpgProcessingRecord;
use Carbon\CarbonImmutable;
use Database\Factories\FgRemarkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $fg_remark_relatable_type
 * @property string $fg_remark_relatable_id
 * @property string $body
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read Model $fgRemarkRelatable
 */
class FgRemark extends Model
{
    /** @use HasFactory<FgRemarkFactory> */
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'fg_remark_relatable_id' => 'string',
    ];

    protected $fillable = [
        'fg_remark_relatable_id',
        'fg_remark_relatable_type',
        'body',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function algorithmRecord(): MorphTo
    {
        return $this->morphTo(AlgorithmRecord::class, 'fg_remark_relatable_type', 'fg_remark_relatable_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function avgProcessorProcessingRecord(): MorphTo
    {
        return $this->morphTo(AvgProcessorProcessingRecord::class, 'fg_remark_relatable_type', 'fg_remark_relatable_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function avgResponsibleProcessingRecord(): MorphTo
    {
        return $this->morphTo(AvgResponsibleProcessingRecord::class, 'fg_remark_relatable_type', 'fg_remark_relatable_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function dataBreachRecord(): MorphTo
    {
        return $this->morphTo(DataBreachRecord::class, 'fg_remark_relatable_type', 'fg_remark_relatable_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function wpgProcessingRecord(): MorphTo
    {
        return $this->morphTo(WpgProcessingRecord::class, 'fg_remark_relatable_type', 'fg_remark_relatable_id');
    }
}
