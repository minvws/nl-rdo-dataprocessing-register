<?php

declare(strict_types=1);

namespace App\Filament\Resources\WpgProcessingRecordResource;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;

use function __;

class WpgProcessingRecordResourceInfolist
{
    public static function stepsInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->extraAttributes(['class' => 'vertical'])
            ->schema([
                ProcessingRecordTabs::make()
                    ->tabs([
                        Tab::make(__('wpg_processing_record.step_processing_name'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessingName()),
                        Tab::make(__('wpg_processing_record.step_responsible'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getResponsible()),
                        Tab::make(__('wpg_processing_record.step_processor'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessor()),
                        Tab::make(__('wpg_processing_record.step_receiver'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getReceiver()),
                        Tab::make(__('wpg_processing_record.step_wpg_goal'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessingGoal()),
                        Tab::make(__('wpg_processing_record.step_special_police_data'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getSpecialPoliceData()),
                        Tab::make(__('wpg_processing_record.step_decision_making'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getDecisionMaking()),
                        Tab::make(__('wpg_processing_record.step_system_application'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getSystems()),
                        Tab::make(__('wpg_processing_record.step_security'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getSecurity()),
                        Tab::make(__('wpg_processing_record.step_geb_dpia'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getGebDpia()),
                        Tab::make(__('wpg_processing_record.step_contact_person'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getContactPersons()),
                        Tab::make(__('wpg_processing_record.step_attachments'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getAttachments()),
                        Tab::make(__('wpg_processing_record.step_remarks'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getRemarks()),
                        Tab::make(__('wpg_processing_record.step_categories_involved'))
                            ->schema(WpgProcessingRecordResourceInfolistSchemas::getCategoriesInvolved()),
                    ]),
            ]);
    }

    public static function onePageInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('wpg_processing_record.step_processing_name'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_responsible'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_processor'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessor())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_receiver'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getReceiver())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_wpg_goal'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getProcessingGoal())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_special_police_data'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getSpecialPoliceData())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_decision_making'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getDecisionMaking())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_system_application'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getSystems())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_security'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getSecurity())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_geb_dpia'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getGebDpia())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_contact_person'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getContactPersons())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_attachments'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getAttachments())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_remarks'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getRemarks())
                    ->compact()
                    ->aside(),
                Section::make(__('wpg_processing_record.step_categories_involved'))
                    ->schema(WpgProcessingRecordResourceInfolistSchemas::getCategoriesInvolved())
                    ->compact()
                    ->aside(),
            ]);
    }
}
