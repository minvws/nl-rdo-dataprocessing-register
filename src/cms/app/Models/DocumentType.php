<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\DocumentCollection;
use Database\Factories\DocumentTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read DocumentCollection $documents
 */
class DocumentType extends LookupListModel
{
    /** @use HasFactory<DocumentTypeFactory> */
    use HasFactory;

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
