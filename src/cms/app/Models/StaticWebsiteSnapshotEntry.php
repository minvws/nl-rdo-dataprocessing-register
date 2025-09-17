<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\StaticWebsiteSnapshotEntryCollection;
use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\StaticWebsiteSnapshotEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property CarbonImmutable|null $end_date
 * @property UuidInterface $last_static_website_check_id
 * @property UuidInterface $snapshot_id
 * @property CarbonImmutable $start_date
 * @property string $url
 *
 * @property-read StaticWebsiteCheck $lastStaticWebsiteCheck
 * @property-read Snapshot $snapshot
 */
class StaticWebsiteSnapshotEntry extends Model
{
    /** @use HasFactory<StaticWebsiteSnapshotEntryFactory> */
    use HasFactory;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = StaticWebsiteSnapshotEntryCollection::class;
    protected $fillable = [
        'last_static_website_check_id',
        'snapshot_id',
        'url',
        'start_date',
        'end_date',
    ];

    public function casts(): array
    {
        return [
            'last_static_website_check_id' => UuidCast::class,
            'end_date' => 'datetime',
            'snapshot_id' => UuidCast::class,
            'start_date' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<StaticWebsiteCheck, $this>
     */
    public function lastStaticWebsiteCheck(): BelongsTo
    {
        return $this->belongsTo(StaticWebsiteCheck::class);
    }

    /**
     * @return BelongsTo<Snapshot, $this>
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
