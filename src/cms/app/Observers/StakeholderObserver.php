<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;

class StakeholderObserver
{
    public function deleting(Stakeholder $stakeholder): void
    {
        $stakeholder->stakeholderDataItems()->each(static function (StakeholderDataItem $stakeholderDataItem): void {
            $stakeholderDataItem->delete();
        });
    }

    public function saving(Stakeholder $stakeholder): void
    {
        if (
            $stakeholder->biometric === false
            && $stakeholder->faith_or_belief === false
            && $stakeholder->genetic === false
            && $stakeholder->health === false
            && $stakeholder->political_attitude === false
            && $stakeholder->race_or_ethnicity === false
            && $stakeholder->sexual_life === false
            && $stakeholder->trade_association_membership === false
        ) {
            $stakeholder->special_collected_data_explanation = null;
        }
    }
}
