<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Forms\Components\ProcessingRecordWizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;

use function __;

class AvgProcessorProcessingRecordResourceForm
{
    public static function stepsForm(Form $form): Form
    {
        return $form
            ->schema([
                ProcessingRecordWizard::make()
                    ->schema([
                        Step::make(__('avg_processor_processing_record.step_processing_name'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessingName()),
                        Step::make(__('avg_processor_processing_record.step_responsible'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getResponsible()),
                        Step::make(__('avg_processor_processing_record.step_processor'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessors()),
                        Step::make(__('avg_processor_processing_record.step_receiver'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getReceiver()),
                        Step::make(__('avg_processor_processing_record.step_processing_goal'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessingGoal()),
                        Step::make(__('avg_processor_processing_record.step_involved_data'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getInvolvedData()),
                        Step::make(__('avg_processor_processing_record.step_decision_making'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getDecisionMaking()),
                        Step::make(__('avg_processor_processing_record.step_system'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getSystem()),
                        Step::make(__('avg_processor_processing_record.step_security'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getSecurity()),
                        Step::make(__('avg_processor_processing_record.step_passthrough'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getPassthrough()),
                        Step::make(__('avg_processor_processing_record.step_geb_pia'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getGebPia()),
                        Step::make(__('avg_processor_processing_record.step_contact_person'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getContactPerson()),
                        Step::make(__('avg_processor_processing_record.step_attachments'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getAttachments()),
                        Step::make(__('avg_processor_processing_record.step_remarks'))
                            ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getRemarks()),
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
                Section::make(__('avg_processor_processing_record.step_processing_name'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_responsible'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_processor'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessors())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_receiver'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_processing_goal'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_involved_data'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getInvolvedData())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_decision_making'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_system'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getSystem())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_security'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_passthrough'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getPassthrough())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_geb_pia'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getGebPia())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_contact_person'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getContactPerson())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_attachments'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_remarks'))
                    ->schema(AvgProcessorProcessingRecordResourceFormSchemas::getRemarks())
                    ->compact()
                    ->aside(),
            ]);
    }
}
