<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Http\Requests\CrudRequest;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

abstract class CrudService
{
    protected ?Closure $beforeCallback = null;

    protected ?Closure $afterCallback = null;

    protected bool $withEvents = true;

    protected bool $withModelEvents = true;

    public function setBeforeCallback(?Closure $beforeStoreCallback): self
    {
        $this->beforeCallback = $beforeStoreCallback;

        return $this;
    }

    public function setAfterCallback(?Closure $afterSaveCallback): self
    {
        $this->afterCallback = $afterSaveCallback;

        return $this;
    }

    public function withEvents(): static
    {
        $this->withEvents = true;

        return $this;
    }

    public function withoutEvents(): static
    {
        $this->withEvents = false;

        return $this;
    }

    public function withModelEvents(): static
    {
        $this->withModelEvents = true;

        return $this;
    }

    public function withoutModelEvents(): static
    {
        $this->withModelEvents = false;

        return $this;
    }

    protected function modelHasAttribute(Model $model, string $attribute): bool
    {
        // If it's not a relation we treat it as an attribute. There is currently no method to determine the attributes of a model without querying the database.
        return !$this->modelHasRelation($model, $attribute);
    }

    protected function modelHasRelation(Model $model, string $relation): bool
    {
        return method_exists($model, $relation) && call_user_func([$model, $relation]) instanceof Relation;
    }

    protected function getRelationType(Model $model, string $relation): Relation
    {
        return call_user_func([$model, $relation]);
    }

    protected function setAttribute(Model $model, string $attribute, mixed $value): Model
    {
        $setter = 'set' . Str::of($attribute)->camel()->ucfirst();

        if (method_exists($model, $setter)) {
            call_user_func([$model, $setter], $value);

            return $model;
        }

        $model->{$attribute} = $value;

        return $model;
    }

    protected function setRelation(Model $model, string $attribute, mixed $value): Model
    {
        $setter = 'set' . Str::of($attribute)->camel()->ucfirst()->plural();

        if (method_exists($model, $setter)) {
            call_user_func([$model, $setter], $value);

            return $model;
        }

        $setter = 'set' . Str::of($attribute)->camel()->ucfirst();

        if (method_exists($model, $setter)) {
            call_user_func([$model, $setter], $value);

            return $model;
        }

        $relation = $this->getRelationType($model, $attribute);

        if ($relation instanceof HasOneOrMany) {
            $relationModel = $relation->getModel();

            $values = $relationModel->findOrFail($value);

            $relation->saveMany(is_iterable($values) ? $values : [$values]);

            return $model;
        }

        if ($relation instanceof BelongsTo) {
            $relation->associate($value);

            return $model;
        }

        if ($relation instanceof BelongsToMany) {
            $relation->sync($value);

            return $model;
        }

        $model->{$attribute} = $value;

        return $model;
    }

    protected function isExcludedFieldName(string $name): bool
    {
        return in_array($name, [
            'submit',
            'submit-another',
            '_method',
            '_token',
        ], true);
    }

    protected function beforeCallback(Model $model, CrudRequest $request): Model
    {
        if (null !== $this->beforeCallback) {
            call_user_func($this->beforeCallback, $model, $request);
        }

        return $model;
    }

    protected function afterCallback(Model $model, CrudRequest $request): Model
    {
        if (null !== $this->afterCallback) {
            call_user_func($this->afterCallback, $model, $request);
        }

        return $model;
    }
}
