<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Casts\CalendarDateCast;
use App\ValueObjects\CalendarDate;
use Webmozart\Assert\Assert;

/**
 * @property CalendarDate|null $review_at
 */
trait IsReviewable
{
    final public function initializeIsReviewable(): void
    {
        $this->mergeCasts(['review_at' => CalendarDateCast::class]);
        $this->mergeFillable(['review_at']);
    }

    final public function getReviewAt(): ?CalendarDate
    {
        $reviewAt = $this->getAttribute('review_at');
        Assert::nullOrIsInstanceOf($reviewAt, CalendarDate::class);

        return $reviewAt;
    }

    final public function setReviewAt(?CalendarDate $reviewAt): void
    {
        $this->setAttribute('review_at', $reviewAt);
    }
}
