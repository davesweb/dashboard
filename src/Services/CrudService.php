<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager;
use Davesweb\Dashboard\Http\Requests\CrudRequest;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;

abstract class CrudService
{
    protected ?Closure $beforeCallback = null;

    protected ?Closure $afterCallback = null;

    protected bool $withEvents = true;

    protected bool $withModelEvents = true;

    protected DatabaseManager $databaseManager;

    private TranslatesModelAttributes $translator;

    public function __construct(TranslatesModelAttributes $translator, DatabaseManager $databaseManager)
    {
        $this->translator      = $translator;
        $this->databaseManager = $databaseManager;
    }

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

    protected function isTranslatedField(string $field, mixed $value): bool
    {
        return is_iterable($value) && isset($value['translated']);
    }

    protected function getAllTranslatedFields(CrudRequest $request): iterable
    {
        $fields = [];

        foreach ($request->post() as $key => $value) {
            if ($this->isTranslatedField($key, $value)) {
                $fields[$key] = $value['translated'];
            }
        }

        return $fields;
    }

    protected function getAllAttributeFields(CrudRequest $request, Model $model): iterable
    {
        $fields = [];

        foreach ($request->post() as $key => $value) {
            if ($this->isExcludedFieldName($key) || $this->isTranslatedField($key, $value)) {
                continue;
            }

            if (!$this->modelHasRelation($model, $key)) {
                $fields[$key] = $value;
            }
        }

        return $fields;
    }

    protected function getAllRelationFieldsBeforeSave(CrudRequest $request, Model $model): iterable
    {
        $fields = [];

        foreach ($request->post() as $key => $value) {
            if ($this->isExcludedFieldName($key)) {
                continue;
            }

            if ($this->modelHasRelation($model, $key) && $this->isBeforeRelation($this->getRelationType($model, $key))) {
                $fields[$key] = $value;
            }
        }

        return $fields;
    }

    protected function getAllRelationFieldsAfterSave(CrudRequest $request, Model $model): iterable
    {
        $fields = [];

        foreach ($request->post() as $key => $value) {
            if ($this->isExcludedFieldName($key)) {
                continue;
            }

            if ($this->modelHasRelation($model, $key) && !$this->isBeforeRelation($this->getRelationType($model, $key))) {
                $fields[$key] = $value;
            }
        }

        return $fields;
    }

    protected function getRelationType(Model $model, string $relation): Relation
    {
        return call_user_func([$model, $relation]);
    }

    protected function isBeforeRelation(Relation $relation): bool
    {
        return !$relation instanceof BelongsToMany;
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

    protected function setTranslations(Model $model, iterable $translatedFields): Model
    {
        $translationsPerLocale = [];

        foreach ($translatedFields as $attribute => $translations) {
            foreach ($translations as $locale => $translated) {
                $translationsPerLocale[$locale][$attribute] = $translated;
            }
        }

        foreach ($translationsPerLocale as $locale => $translations) {
            $this->translator->set($model, $locale, $translations);
        }

        return $model;
    }

    protected function setRelationBefore(Model $model, string $attribute, mixed $value): Model
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

        // Todo: is this a before or after?
        if ($relation instanceof BelongsTo) {
            $relation->associate($value);

            return $model;
        }

        // Fallback, still needed?
        $model->{$attribute} = $value;

        return $model;
    }

    protected function setRelationAfter(Model $model, string $attribute, mixed $value): Model
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

        // Todo: is this a before or after?
        //if ($relation instanceof BelongsTo) {
        //    $relation->associate($value);
        //
        //    return $model;
        //}

        if ($relation instanceof BelongsToMany) {
            $relation->sync($value);

            return $model;
        }

        // Fallback, still needed?
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
