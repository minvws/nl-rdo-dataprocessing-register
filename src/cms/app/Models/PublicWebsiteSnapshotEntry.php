<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\PublicWebsiteSnapshotEntryCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\PublicWebsiteSnapshotEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property CarbonImmutable|null $end_date
 * @property UuidInterface $last_public_website_check_id
 * @property UuidInterface $snapshot_id
 * @property CarbonImmutable $start_date
 * @property string $url
 *
 * @property-read PublicWebsiteCheck $lastPublicWebsiteCheck
 * @property-read Snapshot $snapshot
 */
class PublicWebsiteSnapshotEntry extends Model
{
    /** @use HasFactory<PublicWebsiteSnapshotEntryFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = PublicWebsiteSnapshotEntryCollection::class;
    protected $fillable = [
        'last_public_website_check_id',
        'snapshot_id',
        'url',
        'start_date',
        'end_date',
    ];

    public function casts(): array
    {
        return [
            'last_public_website_check_id' => UuidCast::class,
            'end_date' => 'datetime',
            'snapshot_id' => UuidCast::class,
            'start_date' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<PublicWebsiteCheck, $this>
     */
    public function lastPublicWebsiteCheck(): BelongsTo
    {
        return $this->belongsTo(PublicWebsiteCheck::class);
    }

    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
