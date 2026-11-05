<?php

declare(strict_types=1);

namespace App\Filament\Resources\SystemResource;

use App\Facades\Authentication;
use App\Models\System;
use App\Services\NameSimilarityService;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

use function __;
use function array_intersect;
use function collect;
use function implode;

class SystemResourceForm
{
    private const SIMILAR_DESCRIPTION_MINIMUM_PERCENTAGE = 90.0;
    private const SIMILAR_DESCRIPTION_MINIMUM_WORD_LENGTH = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(self::getSchema());
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(): array
    {
        return [
            TextInput::make('description')
                ->label(__('system.description'))
                ->unique(ignoreRecord: true, modifyRuleUsing: static function (Unique $rule): void {
                    $rule->where('organisation_id', Authentication::organisation()->id->toString());
                })
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->belowContent(static function (?string $state, ?Model $record): ?Text {
                    $similarDescriptions = self::getSimilarDescriptions($state, $record);
                    if ($similarDescriptions === []) {
                        return null;
                    }

                    $descriptions = implode(', ', $similarDescriptions);

                    return Text::make(__('system.similar_descriptions', ['descriptions' => $descriptions]))
                        ->color('warning')
                        ->icon(Heroicon::OutlinedExclamationTriangle);
                }),
        ];
    }

    /**
     * @return list<string>
     */
    private static function getSimilarDescriptions(?string $description, ?Model $record): array
    {
        $normalizedDescription = NameSimilarityService::normalize((string) $description);
        if ($normalizedDescription === '') {
            return [];
        }

        $query = System::query()
            ->where('organisation_id', Authentication::organisation()->id->toString());
        if ($record instanceof System) {
            $query->whereKeyNot($record->getKey());
        }

        /** @var list<string> $existingDescriptions */
        $existingDescriptions = $query
            ->whereNotNull('description')
            ->orderBy('description')
            ->pluck('description')
            ->all();

        $words = NameSimilarityService::words((string) $description, self::SIMILAR_DESCRIPTION_MINIMUM_WORD_LENGTH);

        return collect($existingDescriptions)
            ->filter(static function (string $existingDescription) use ($normalizedDescription, $words): bool {
                $similarity = NameSimilarityService::compare(
                    $normalizedDescription,
                    NameSimilarityService::normalize($existingDescription),
                    self::SIMILAR_DESCRIPTION_MINIMUM_PERCENTAGE,
                );
                if ($similarity !== null) {
                    return true;
                }

                return array_intersect(
                    $words,
                    NameSimilarityService::words($existingDescription, self::SIMILAR_DESCRIPTION_MINIMUM_WORD_LENGTH),
                ) !== [];
            })
            ->values()
            ->all();
    }
}
