<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Events\SavedCrud;
use Davesweb\Dashboard\Events\SavingCrud;
use Davesweb\Dashboard\Http\Requests\StoreCrudRequest;

class StoreCrudService extends CrudService
{
    public function store(StoreCrudRequest $request, Model $model): Model
    {
        if ($this->withEvents) {
            event(new SavingCrud($model));
        }

        $model = $this->beforeCallback($model, $request);

        foreach ($request->post() as $attribute => $value) {
            if ($this->isExcludedFieldName($attribute)) {
                continue;
            }

            if ($this->isTranslatedField($attribute, $value)) {
                continue;
            }

            if ($this->modelHasRelation($model, $attribute)) {
                $this->setRelation($model, $attribute, $value);
            } else {
                $this->setAttribute($model, $attribute, $value);
            }
        }

        $this->withModelEvents ? $model->save() : $model->saveQuietly();

        $translatedFields = $this->getAllTranslatedFields($request);

        $this->setTranslations($model, $translatedFields);

        $model = $this->afterCallback($model, $request);

        $model = $this->afterCallback($model, $request);

        if ($this->withEvents) {
            event(new SavedCrud($model));
        }

        return $model;
    }

    public function storeQuietly(StoreCrudRequest $request, Model $model, bool $withModelEvents = true): Model
    {
        $this->withoutEvents();

        if (!$withModelEvents) {
            $this->withoutModelEvents();
        }

        $model = $this->store($request, $model);

        if (!$withModelEvents) {
            $this->withModelEvents();
        }

        $this->withEvents();

        return $model;
    }
}
