<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $message
 * @property array $context
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class AdminLogEntry extends Model
{
    use HasTimestamps;
    use HasFactory;

    protected $casts = [
        'context' => 'array',
    ];

    protected $fillable = [
        'message',
        'context',
    ];
}
