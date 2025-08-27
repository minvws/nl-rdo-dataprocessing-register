<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EntityNumberType;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\EntityNumberCounterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $number
 * @property string $prefix
 * @property EntityNumberType $type
 */
class EntityNumberCounter extends Model
{
    /** @use HasFactory<EntityNumberCounterFactory> */
    use HasFactory;
    use HasUuidAsId;

    protected $fillable = [
        'number',
        'prefix',
        'type',
    ];
    public $timestamps = false;

    public function casts(): array
    {
        return [
            'type' => EntityNumberType::class,
        ];
    }
}
