<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Wpg\WpgProcessingRecord;

class WpgProcessingRecordObserver
{
    public function saving(WpgProcessingRecord $wpgProcessingRecord): void
    {
        $this->resetProcessors($wpgProcessingRecord);
        $this->resetReceivers($wpgProcessingRecord);
        $this->resetDecisionMaking($wpgProcessingRecord);
        $this->resetSystems($wpgProcessingRecord);
        $this->resetSecurity($wpgProcessingRecord);
        $this->resetCategoriesInvolved($wpgProcessingRecord);
    }

    private function resetProcessors(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->has_processors === false) {
            $wpgProcessingRecord->processors()->delete();
        }
    }

    private function resetReceivers(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->article_17_a === false) {
            $wpgProcessingRecord->explanation_transfer = null;
        }
    }

    private function resetDecisionMaking(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->decision_making === false) {
            $wpgProcessingRecord->logic = null;
            $wpgProcessingRecord->consequences = null;
        }
    }

    private function resetSystems(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->has_systems === false) {
            $wpgProcessingRecord->systems()->delete();
        }
    }

    private function resetSecurity(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->has_security === false) {
            $wpgProcessingRecord->measures_implemented = false;
            $wpgProcessingRecord->other_measures = false;
            $wpgProcessingRecord->measures_description = null;

            $wpgProcessingRecord->has_pseudonymization = false;
        }

        if ($wpgProcessingRecord->has_pseudonymization === false) {
            $wpgProcessingRecord->pseudonymization = null;
        }
    }

    private function resetCategoriesInvolved(WpgProcessingRecord $wpgProcessingRecord): void
    {
        if ($wpgProcessingRecord->third_parties === false) {
            $wpgProcessingRecord->third_party_explanation = null;
        }
    }
}
