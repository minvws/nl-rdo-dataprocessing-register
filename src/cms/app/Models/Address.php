<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $addressable_type
 * @property string $addressable_id
 * @property string|null $address
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postbox
 * @property string|null $postbox_postal_code
 * @property string|null $postbox_city
 * @property string|null $postbox_country
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
class Address extends Model
{
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsId;
    use SoftDeletes;

    protected $casts = [
        'addressable_id' => 'string',
    ];

    protected $fillable = [
        'address',
        'postal_code',
        'city',
        'country',
        'postbox',
        'postbox_postal_code',
        'postbox_city',
        'postbox_country',
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
