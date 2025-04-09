<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasAddress;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSnapshots;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Contracts\SnapshotSource;
use App\Models\Wpg\WpgProcessingRecord;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $import_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string|null $contact_person_position_id
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Address|null $address
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 * @property-read ContactPersonPosition|null $contactPersonPosition
 * @property-read Collection<int, Snapshot> $snapshots
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 */
class ContactPerson extends Model implements SnapshotSource
{
    use HasAddress;
    use HasFactory;
    use HasOrganisation;
    use HasSnapshots;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $table = 'contact_persons';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'contact_person_position_id',

        'name',
        'role',
        'email',
        'phone',
        'import_id',
    ];

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'contact_person_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'contact_person_relatable');
    }

    /**
     * @return BelongsTo<ContactPersonPosition, $this>
     */
    public function contactPersonPosition(): BelongsTo
    {
        return $this->belongsTo(ContactPersonPosition::class, 'contact_person_position_id');
    }

    /**
     * @return MorphToMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(WpgProcessingRecord::class, 'contact_person_relatable');
    }
}
