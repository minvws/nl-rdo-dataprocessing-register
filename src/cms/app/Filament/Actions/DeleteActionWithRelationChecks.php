<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\Relation;
use Webmozart\Assert\Assert;

use function __;

class DeleteActionWithRelationChecks extends DeleteAction
{
    /** @var array<Relation> $relations */
    private array $relations = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function ($data, $record): void {
            foreach ($this->relations as $relation) {
                if ($relation->getQuery()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title(__('general.error'))
                        ->body(__('error.delete_abort_constraints_not_empty'))
                        ->send();

                    $this->failure();

                    return;
                }
            }

            $record->delete();

            $this->success();
        });
    }

    /**
     * @param array<string> $relations
     */
    public function relations(array $relations): self
    {
        foreach ($relations as $relation) {
            $this->addRelation($relation);
        }

        return $this;
    }

    private function addRelation(string $relation): void
    {
        Assert::notNull($this->record);
        Assert::stringNotEmpty($relation);
        Assert::methodExists($this->record, $relation);

        $relation = $this->record->$relation();
        Assert::isInstanceOf($relation, Relation::class);

        $this->relations[] = $relation;
    }
}
