<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EntityNumberType;
use App\Models\Concerns\HasUuidAsId;
use Database\Factories\EntityNumberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property EntityNumberType $type
 * @property string $number
 */
class EntityNumber extends Model
{
    /** @use HasFactory<EntityNumberFactory> */
    use HasFactory;
    use HasUuidAsId;

    protected $fillable = [
        'number',
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
