<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Upload;

use App\Config\Config;
use App\Enums\Media\MediaGroup;
use App\Facades\Authentication;
use App\Rules\Virusscanner;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Webmozart\Assert\Assert;

use function __;
use function app;

class AttachmentFileField extends SpatieMediaLibraryFileUpload
{
    public const MEGABYTE = 1024 * 1024;

    public static function make(string $name): static
    {
        $types = Config::array('media-library.permitted_file_types.attachment');
        Assert::allString($types);

        return parent::make($name)
            ->label(__('general.attachments'))
            ->collection(MediaGroup::ATTACHMENTS->value)
            ->downloadable()
            ->multiple()
            ->acceptedFileTypes($types)
            ->maxSize(self::MEGABYTE * 20)
            ->properties([
                'organisation_id' => Authentication::organisation()->id->toString(),
            ])
            ->rules([
                app()->get(Virusscanner::class),
            ])
            ->columnSpanFull();
    }
}
