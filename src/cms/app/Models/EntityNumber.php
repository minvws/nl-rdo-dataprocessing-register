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
 * @property string $number
 */
class EntityNumber extends Model
{
    use HasFactory;
    use HasUuidAsKey;

    protected $casts = [
        'id' => 'string',
        'type' => EntityNumberType::class,
    ];

    protected $fillable = [
        'number',
        'type',
    ];

    public $timestamps = false;
}
