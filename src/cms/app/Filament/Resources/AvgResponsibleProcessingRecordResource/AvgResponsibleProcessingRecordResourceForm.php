<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordResource;

use App\Filament\Forms\Components\ProcessingRecordWizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;

use function __;

class AvgResponsibleProcessingRecordResourceForm
{
    public static function stepsForm(Form $form): Form
    {
        return $form
            ->schema([
                ProcessingRecordWizard::make()
                    ->schema([
                        Step::make(__('avg_responsible_processing_record.step_processing_name'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessingName()),
                        Step::make(__('avg_responsible_processing_record.step_responsible'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getResponsible()),
                        Step::make(__('avg_responsible_processing_record.step_processor'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessor()),
                        Step::make(__('avg_responsible_processing_record.step_receiver'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getReceiver()),
                        Step::make(__('avg_responsible_processing_record.step_processing_goal'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessingGoal()),
                        Step::make(__('avg_responsible_processing_record.step_stakeholder_data'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getStakeholder()),
                        Step::make(__('avg_responsible_processing_record.step_decision_making'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getDecisionMaking()),
                        Step::make(__('avg_responsible_processing_record.step_system'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getSystem()),
                        Step::make(__('avg_responsible_processing_record.step_security'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getSecurity()),
                        Step::make(__('avg_responsible_processing_record.step_passthrough'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getPassthrough()),
                        Step::make(__('avg_responsible_processing_record.step_geb_dpia'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getGebDpia()),
                        Step::make(__('avg_responsible_processing_record.step_contact_person'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getContactPerson()),
                        Step::make(__('avg_responsible_processing_record.step_attachments'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getAttachments()),
                        Step::make(__('avg_responsible_processing_record.step_remarks'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getRemarks()),
                        Step::make(__('avg_responsible_processing_record.step_publish'))
                            ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getPublish()),
                    ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function onePageForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('avg_responsible_processing_record.step_processing_name'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_responsible'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_processor'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessor())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_receiver'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_processing_goal'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_stakeholder_data'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getStakeholder())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_decision_making'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_system'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getSystem())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_security'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_passthrough'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getPassthrough())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_geb_dpia'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getGebDpia())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_contact_person'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getContactPerson())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_attachments'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_remarks'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getRemarks())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_publish'))
                    ->schema(AvgResponsibleProcessingRecordResourceFormSchemas::getPublish())
                    ->compact()
                    ->aside(),
            ]);
    }
}
