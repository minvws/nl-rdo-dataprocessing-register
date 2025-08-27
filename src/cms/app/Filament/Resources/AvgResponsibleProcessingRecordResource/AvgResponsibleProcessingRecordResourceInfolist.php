<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordResource;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;

use function __;

class AvgResponsibleProcessingRecordResourceInfolist
{
    public static function stepsInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->extraAttributes(['class' => 'vertical'])
            ->schema([
                ProcessingRecordTabs::make()
                    ->tabs([
                        Tab::make(__('avg_responsible_processing_record.step_processing_name'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessingName()),
                        Tab::make(__('avg_responsible_processing_record.step_responsible'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getResponsible()),
                        Tab::make(__('avg_responsible_processing_record.step_processor'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessor()),
                        Tab::make(__('avg_responsible_processing_record.step_receiver'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getReceiver()),
                        Tab::make(__('avg_responsible_processing_record.step_processing_goal'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessingGoal()),
                        Tab::make(__('avg_responsible_processing_record.step_stakeholder_data'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getStakeholder()),
                        Tab::make(__('avg_responsible_processing_record.step_decision_making'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getDecisionMaking()),
                        Tab::make(__('avg_responsible_processing_record.step_system'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getSystem()),
                        Tab::make(__('avg_responsible_processing_record.step_security'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getSecurity()),
                        Tab::make(__('avg_responsible_processing_record.step_passthrough'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getPassthrough()),
                        Tab::make(__('avg_responsible_processing_record.step_geb_dpia'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getGebDpia()),
                        Tab::make(__('avg_responsible_processing_record.step_contact_person'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getContactPerson()),
                        Tab::make(__('avg_responsible_processing_record.step_attachments'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getAttachments()),
                        Tab::make(__('avg_responsible_processing_record.step_remarks'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getRemarks()),
                        Tab::make(__('avg_responsible_processing_record.step_publish'))
                            ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getPublish()),
                    ]),
            ]);
    }

    public static function onePageInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('avg_responsible_processing_record.step_processing_name'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_responsible'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_processor'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessor())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_receiver'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_processing_goal'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_stakeholder_data'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getStakeholder())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_decision_making'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_system'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getSystem())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_security'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_passthrough'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getPassthrough())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_geb_dpia'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getGebDpia())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_contact_person'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getContactPerson())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_attachments'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_remarks'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getRemarks())
                    ->compact()
                    ->aside(),
                Section::make(__('avg_responsible_processing_record.step_publish'))
                    ->schema(AvgResponsibleProcessingRecordResourceInfolistSchemas::getPublish())
                    ->compact()
                    ->aside(),
            ]);
    }
}
