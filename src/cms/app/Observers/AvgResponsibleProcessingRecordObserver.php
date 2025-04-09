<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Avg\AvgResponsibleProcessingRecord;

use function __;

class AvgResponsibleProcessingRecordObserver
{
    public function saving(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        $this->resetProcessors($avgResponsibleProcessingRecord);
        $this->resetDecisionMaking($avgResponsibleProcessingRecord);
        $this->resetSystems($avgResponsibleProcessingRecord);
        $this->resetSecurity($avgResponsibleProcessingRecord);
        $this->resetPassthrough($avgResponsibleProcessingRecord);
        $this->resetGebDpia($avgResponsibleProcessingRecord);
    }

    private function resetProcessors(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->has_processors === false) {
            $avgResponsibleProcessingRecord->processors()->delete();
        }
    }

    private function resetDecisionMaking(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->decision_making === false) {
            $avgResponsibleProcessingRecord->logic = null;
            $avgResponsibleProcessingRecord->importance_consequences = null;
        }
    }

    private function resetSystems(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->has_systems === false) {
            $avgResponsibleProcessingRecord->systems()->delete();
        }
    }

    private function resetSecurity(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->has_security === false) {
            $avgResponsibleProcessingRecord->measures_implemented = false;
            $avgResponsibleProcessingRecord->other_measures = false;
            $avgResponsibleProcessingRecord->measures_description = null;

            $avgResponsibleProcessingRecord->has_pseudonymization = false;
        }

        if ($avgResponsibleProcessingRecord->has_pseudonymization === false) {
            $avgResponsibleProcessingRecord->pseudonymization = null;
        }
    }

    private function resetPassthrough(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->outside_eu === false) {
            $avgResponsibleProcessingRecord->country = null;
            $avgResponsibleProcessingRecord->outside_eu_protection_level = false;
            $avgResponsibleProcessingRecord->outside_eu_description = null;
            $avgResponsibleProcessingRecord->outside_eu_protection_level_description = null;
        }

        if ($avgResponsibleProcessingRecord->country !== __('general.country_other')) {
            $avgResponsibleProcessingRecord->country_other = null;
        }

        if ($avgResponsibleProcessingRecord->outside_eu_protection_level === true) {
            $avgResponsibleProcessingRecord->outside_eu_protection_level_description = null;
        }
    }

    private function resetGebDpia(AvgResponsibleProcessingRecord $avgResponsibleProcessingRecord): void
    {
        if ($avgResponsibleProcessingRecord->geb_dpia_executed === true) {
            $avgResponsibleProcessingRecord->geb_dpia_automated = false;
            $avgResponsibleProcessingRecord->geb_dpia_large_scale_processing = false;
            $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = false;
            $avgResponsibleProcessingRecord->geb_dpia_list_required = false;
            $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = false;
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
            return;
        }

        if ($avgResponsibleProcessingRecord->geb_dpia_automated === true) {
            $avgResponsibleProcessingRecord->geb_dpia_large_scale_processing = false;
            $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = false;
            $avgResponsibleProcessingRecord->geb_dpia_list_required = false;
            $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = false;
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
            return;
        }

        if ($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing === true) {
            $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = false;
            $avgResponsibleProcessingRecord->geb_dpia_list_required = false;
            $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = false;
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
            return;
        }

        if ($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring === true) {
            $avgResponsibleProcessingRecord->geb_dpia_list_required = false;
            $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = false;
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
            return;
        }

        if ($avgResponsibleProcessingRecord->geb_dpia_list_required === true) {
            $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = false;
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
            return;
        }

        if ($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 === true) {
            $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = false;
        }
    }
}
