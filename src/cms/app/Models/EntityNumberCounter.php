<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EntityNumberType;
use App\Models\Concerns\HasUuidAsKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property EntityNumberType $type
 * @property string $prefix
 * @property int $number
 */
class EntityNumberCounter extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'type' => EntityNumberType::class,
    ];

    protected $fillable = [
        'type',
        'prefix',
        'number',
    ];

    public $timestamps = false;
}
