<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Events\UpdatedCrud;
use Davesweb\Dashboard\Events\UpdatingCrud;
use Davesweb\Dashboard\Http\Requests\UpdateCrudRequest;

class UpdateCrudService extends CrudService
{
    public function update(UpdateCrudRequest $request, Model $model): Model
    {
        return $this->databaseManager->transaction(function () use ($request, $model) {
            if ($this->withEvents) {
                event(new UpdatingCrud($model));
            }

            $model = $this->beforeCallback($model, $request);

            $attributes = $this->getAllAttributeFields($request, $model);
            foreach ($attributes as $attribute => $value) {
                $this->setAttribute($model, $attribute, $value);
            }

            $beforeRelations = $this->getAllRelationFieldsBeforeSave($request, $model);
            foreach ($beforeRelations as $relation => $value) {
                $this->setRelationBefore($model, $relation, $value);
            }

            $this->withModelEvents ? $model->update() : $model->updateQuietly();

            $afterRelations = $this->getAllRelationFieldsAfterSave($request, $model);
            foreach ($afterRelations as $relation => $value) {
                $this->setRelationAfter($model, $relation, $value);
            }

            $translatedFields = $this->getAllTranslatedFields($request);

            $this->setTranslations($model, $translatedFields);

            $model = $this->afterCallback($model, $request);

            if ($this->withEvents) {
                event(new UpdatedCrud($model));
            }

            return $model;
        });
    }

    public function updateQuietly(UpdateCrudRequest $request, Model $model, bool $withModelEvents = true): Model
    {
        $this->withoutEvents();

        if (!$withModelEvents) {
            $this->withoutModelEvents();
        }

        $model = $this->update($request, $model);

        if (!$withModelEvents) {
            $this->withModelEvents();
        }

        $this->withEvents();

        return $model;
    }
}
