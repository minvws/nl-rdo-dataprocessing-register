<?php

declare(strict_types=1);

namespace App\Filament\Resources\PublicWebsiteTreeResource\Pages;

use App\Filament\Resources\PublicWebsiteTreeResource;
use App\Models\PublicWebsiteTree;
use Carbon\CarbonImmutable;
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use SolutionForest\FilamentTree\Resources\Pages\TreePage;
use Webmozart\Assert\Assert;

use function __;
use function sprintf;

class ListTree extends TreePage
{
    protected static string $resource = PublicWebsiteTreeResource::class;
    protected static ?string $breadcrumb = '';

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('public_website_tree.create')),
        ];
    }

    public static function canAccess(): bool
    {
        return Gate::allows('update', PublicWebsiteTree::class);
    }

    public function getTreeRecordDescription(?Model $record = null): HtmlString
    {
        Assert::isInstanceOf($record, PublicWebsiteTree::class);

        $slug = $record->slug;
        $organisationName = $record->organisation?->name;
        $publicationDate = $this->getPublicationDate($record->public_from);

        return Str::of(sprintf('<span class="font-mono">%s</span>, %s %s', $slug, $organisationName, $publicationDate))
            ->toHtmlString();
    }

    private function getPublicationDate(?CarbonImmutable $publicationDate): ?string
    {
        if ($publicationDate === null) {
            return __('public_website_tree.public_from_null');
        }

        if ($publicationDate->isFuture()) {
            return __('public_website_tree.public_from_future', ['publicationDate' => $publicationDate->format('d-m-Y')]);
        }

        return null;
    }

    public function getTreeRecordIcon(?Model $record = null): string
    {
        Assert::isInstanceOf($record, PublicWebsiteTree::class);

        $publicationDate = $record->public_from;

        if ($publicationDate === null || $publicationDate->isFuture()) {
            return 'heroicon-o-eye-slash';
        }

        return 'heroicon-o-eye';
    }
}
