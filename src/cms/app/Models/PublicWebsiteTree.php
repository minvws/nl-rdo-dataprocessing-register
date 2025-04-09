<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasDefaultMediaCollections;
use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Database\Factories\PublicWebsiteTreeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SolutionForest\FilamentTree\Concern\ModelTree;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property ?string $organisation_id
 * @property int $order
 * @property ?string $parent_id
 * @property string $title
 * @property string $slug
 * @property ?CarbonImmutable $public_from
 * @property string $public_website_content
 * @property ?CarbonImmutable $created_at
 * @property ?CarbonImmutable $updated_at
 *
 * @property-read ?Organisation $organisation
 */
class PublicWebsiteTree extends Model implements HasMedia
{
    /**
     * @phpstan-use HasFactory<PublicWebsiteTreeFactory>
     */
    use HasFactory;
    use HasDefaultMediaCollections;
    use HasUuidAsKey;
    use ModelTree;

    protected $casts = [
        'organisation_id' => 'string',
        'order' => 'int',
        'parent_id' => 'string',
        'public_from' => 'datetime',
    ];
    protected $fillable = [
        'organisation_id',
        'order',
        'parent_id',
        'title',
        'slug',
        'public_from',
        'public_website_content',
    ];
    protected $table = 'public_website_tree';

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
