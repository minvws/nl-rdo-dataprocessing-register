<?php

declare(strict_types=1);

namespace App\Vendor\MediaLibrary;

use App\Collections\MediaCollection;
use App\Models\Concerns\HasTimestamps;
use App\Models\Organisation;
use Carbon\CarbonImmutable;
use Database\Factories\Vendor\MediaLibrary\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

use function array_merge;

/**
 * @property ?string $content_hash
 * @property ?string $organisation_id
 * @property ?CarbonImmutable $validated_at
 *
 * @property-read ?Model $model
 * @property-read ?Organisation $organisation
 */
class Media extends BaseMedia
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;
    use HasTimestamps;

    protected static string $collectionClass = MediaCollection::class;

    /**
     * @param array<string, mixed>$attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->casts = array_merge($this->casts, [
            'validated_at' => 'datetime',
        ]);
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
