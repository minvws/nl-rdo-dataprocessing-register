<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\ValueObjects\CalendarDate;

interface Reviewable
{
    public function getReviewAt(): ?CalendarDate;

    public function setReviewAt(?CalendarDate $reviewAt): void;
}
