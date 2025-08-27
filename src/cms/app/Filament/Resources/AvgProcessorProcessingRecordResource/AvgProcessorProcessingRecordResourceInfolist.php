<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;

use function __;

class AvgProcessorProcessingRecordResourceInfolist
{
    public static function stepsInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->extraAttributes(['class' => 'vertical'])
            ->schema([
                ProcessingRecordTabs::make()
                    ->tabs([
                        Tab::make(__('avg_processor_processing_record.step_processing_name'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessingName()),
                        Tab::make(__('avg_processor_processing_record.step_responsible'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getResponsible()),
                        Tab::make(__('avg_processor_processing_record.step_processor'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessors()),
                        Tab::make(__('avg_processor_processing_record.step_receiver'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getReceiver()),
                        Tab::make(__('avg_processor_processing_record.step_processing_goal'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessingGoal()),
                        Tab::make(__('avg_processor_processing_record.step_involved_data'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getInvolvedData()),
                        Tab::make(__('avg_processor_processing_record.step_decision_making'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getDecisionMaking()),
                        Tab::make(__('avg_processor_processing_record.step_system'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getSystem()),
                        Tab::make(__('avg_processor_processing_record.step_security'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getSecurity()),
                        Tab::make(__('avg_processor_processing_record.step_passthrough'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getPassthrough()),
                        Tab::make(__('avg_processor_processing_record.step_geb_pia'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getGebPia()),
                        Tab::make(__('avg_processor_processing_record.step_contact_person'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getContactPerson()),
                        Tab::make(__('avg_processor_processing_record.step_attachments'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getAttachments()),
                        Tab::make(__('avg_processor_processing_record.step_remarks'))
                            ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getRemarks()),
                    ]),
            ]);
    }

    public static function onePageInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('avg_processor_processing_record.step_processing_name'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_responsible'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_processor'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessors())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_receiver'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_processing_goal'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_involved_data'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getInvolvedData())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_decision_making'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_system'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getSystem())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_security'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_passthrough'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getPassthrough())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_geb_pia'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getGebPia())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_contact_person'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getContactPerson())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_attachments'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_processor_processing_record.step_remarks'))
                    ->schema(AvgProcessorProcessingRecordResourceInfolistSchemas::getRemarks())
                    ->compact()
                    ->aside(),
            ]);
    }
}
