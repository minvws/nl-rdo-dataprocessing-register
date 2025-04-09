<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Filament\Resources\Resource;
use App\Models\Contracts\Cloneable;
use App\Models\Contracts\EntityNumerable;
use Filament\Actions\ReplicateAction;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

class CloneAction extends ReplicateAction
{
    protected function setUp(?string $name = null): void
    {
        parent::setUp();

        $this->action(function () {
            $result = $this->process(function (array $data, Cloneable $record): void {
                $this->callBeforeReplicaSaved();

                $this->replica = $record->clone();
            });

            try {
                return $result;
            } finally {
                $this->success();
            }
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'clone';
    }

    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->successRedirectUrl(static function (EntityNumerable&Model $replica): string {
                /** @var class-string<Resource> $resource */
                $resource = Filament::getModelResource($replica);

                $url = $resource::getGlobalSearchResultUrl($replica);
                Assert::string($url);

                return $url;
            });
    }
}
