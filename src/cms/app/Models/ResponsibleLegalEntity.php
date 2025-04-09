<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasUuidAsKey;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
class ResponsibleLegalEntity extends Model
{
    use HasFactory;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'name',
    ];

    protected $table = 'responsible_legal_entity';
}
