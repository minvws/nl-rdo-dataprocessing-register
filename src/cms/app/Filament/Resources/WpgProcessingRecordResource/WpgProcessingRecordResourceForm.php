<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordResource;

use App\Filament\Forms\Components\ProcessingRecordStep;
use App\Filament\Forms\Components\ProcessingRecordWizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

use function __;

class WpgProcessingRecordResourceForm
{
    public static function stepsForm(Form $form): Form
    {
        return $form
            ->schema([
                ProcessingRecordWizard::make()
                    ->schema([
                        ProcessingRecordStep::make(__('wpg_processing_record.step_processing_name'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getProcessingName()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_responsible'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getResponsible()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_processor'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getProcessor()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_receiver'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getReceiver()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_wpg_goal'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getProcessingGoal()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_special_police_data'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getSpecialPoliceData()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_decision_making'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getDecisionMaking()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_system_application'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getSystems()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_security'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getSecurity()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_geb_dpia'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getGebDpia()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_contact_person'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getContactPersons()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_attachments'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getAttachments()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_remarks'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getRemarks()),
                        ProcessingRecordStep::make(__('wpg_processing_record.step_categories_involved'))
                            ->schema(WpgProcessingRecordResourceFormSchemas::getCategoriesInvolved()),
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
                Section::make(__('wpg_processing_record.step_processing_name'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_responsible'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_processor'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getProcessor())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_receiver'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_wpg_goal'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_special_police_data'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getSpecialPoliceData())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_decision_making'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_system_application'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getSystems())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_security'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_geb_dpia'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getGebDpia())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_contact_person'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getContactPersons())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_attachments'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_remarks'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getRemarks())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_categories_involved'))
                    ->schema(WpgProcessingRecordResourceFormSchemas::getCategoriesInvolved())
                    ->compact()
                    ->aside(),
            ]);
    }
}
