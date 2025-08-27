<?php

declare(strict_types=1);

namespace App\Models;

use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Contracts\TenantAware;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $addressable_type
 * @property UuidInterface $addressable_id
 * @property string|null $address
 * @property string|null $postal_code
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postbox
 * @property string|null $postbox_postal_code
 * @property string|null $postbox_city
 * @property string|null $postbox_country
 */
class Address extends Model implements TenantAware
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;

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

    public function casts(): array
    {
        return [
            'addressable_id' => UuidCast::class,
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
