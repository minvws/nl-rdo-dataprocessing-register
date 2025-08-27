<?php

declare(strict_types=1);

namespace App\Models;

use App\Components\Uuid\UuidInterface;
use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Wpg\WpgProcessingRecord;
use Database\Factories\FgRemarkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $fg_remark_relatable_type
 * @property UuidInterface $fg_remark_relatable_id
 * @property string $body
 *
 * @property-read Model $fgRemarkRelatable
 */
class FgRemark extends Model
{
    /** @use HasFactory<FgRemarkFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected $fillable = [
        'fg_remark_relatable_id',
        'fg_remark_relatable_type',
        'body',
    ];

    public function casts(): array
    {
        return [
            'fg_remark_relatable_id' => UuidCast::class,
        ];
    }

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
