<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\AdminLogEntryCollection;
use App\Models\Concerns\HasTimestamps;
use Database\Factories\AdminLogEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $message
 * @property array<string, mixed> $context
 */
class AdminLogEntry extends Model
{
    /** @use HasFactory<AdminLogEntryFactory> */
    use HasFactory;
    use HasTimestamps;

    protected static string $collectionClass = AdminLogEntryCollection::class;
    protected $fillable = [
        'message',
        'context',
    ];

    public function casts(): array
    {
        return [
            'context' => 'array',
        ];
    }
}
