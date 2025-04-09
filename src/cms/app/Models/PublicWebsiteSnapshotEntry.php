<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Database\Factories\PublicWebsiteSnapshotEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $last_public_website_check_id
 * @property string $snapshot_id
 * @property string $url
 * @property CarbonImmutable $start_date
 * @property CarbonImmutable|null $end_date
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @property-read PublicWebsiteCheck $lastPublicWebsiteCheck
 * @property-read Snapshot $snapshot
 */
class PublicWebsiteSnapshotEntry extends Model
{
    /** @use HasFactory<PublicWebsiteSnapshotEntryFactory> */
    use HasFactory;
    use HasUuidAsId;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    protected $fillable = [
        'last_public_website_check_id',
        'snapshot_id',
        'url',
        'start_date',
        'end_date',
    ];

    protected $table = 'public_website_snapshot_entries';

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
