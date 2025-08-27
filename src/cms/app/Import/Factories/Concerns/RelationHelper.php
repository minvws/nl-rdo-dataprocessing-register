<?php

declare(strict_types=1);

namespace App\Import\Factories\Concerns;

use App\Components\Uuid\UuidInterface;
use App\Import\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Webmozart\Assert\Assert;

trait RelationHelper
{
    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param MorphMany<TRelatedModel, TDeclaringModel>|MorphToMany<TRelatedModel, TDeclaringModel> $relation
     * @param array<string, mixed> $data
     * @param Factory<TRelatedModel> $factory
     */
    final protected function createRelations(
        MorphMany|MorphToMany $relation,
        UuidInterface $organisationId,
        array $data,
        string $key,
        Factory $factory,
    ): void {
        $input = Arr::get($data, $key);
        Assert::isArray($input);

        foreach ($input as $relationData) {
            Assert::isMap($relationData);

            $relatedModel = $factory->create($relationData, $organisationId);
            Assert::notNull($relatedModel);

            $relation->save($relatedModel);
        }
    }
}
