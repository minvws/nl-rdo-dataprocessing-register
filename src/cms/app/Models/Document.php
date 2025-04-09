<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasDefaultMediaCollections;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Wpg\WpgProcessingRecord;
use App\Vendor\MediaLibrary\Media;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * @property string $id
 * @property string $name
 * @property CarbonImmutable|null $expires_at
 * @property CarbonImmutable|null $notify_at
 * @property string|null $location
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Collection<int, AlgorithmRecord> $algorithmRecords
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 * @property-read Collection<int, DataBreachRecord> $dataBreachRecords
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 * @property-read MediaCollection<int, Media> $media
 */
class Document extends Model implements HasMedia
{
    use HasDefaultMediaCollections;
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',

        'expires_at' => 'date',
        'notify_at' => 'date',
    ];

    protected $fillable = [
        'name',
        'expires_at',
        'notify_at',
        'location',
    ];

    /**
     * @return MorphToMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): MorphToMany
    {
        return $this->morphedByMany(AlgorithmRecord::class, 'document_relatable');
    }

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'document_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'document_relatable');
    }

    /**
     * @return MorphToMany<DataBreachRecord, $this>
     */
    public function dataBreachRecords(): MorphToMany
    {
        return $this->morphedByMany(DataBreachRecord::class, 'document_relatable');
    }

    /**
     * @return BelongsTo<DocumentType, $this>
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * @return MorphToMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(WpgProcessingRecord::class, 'document_relatable');
    }
}
