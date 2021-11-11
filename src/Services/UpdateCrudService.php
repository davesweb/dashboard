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
        if ($this->withEvents) {
            event(new UpdatingCrud($model));
        }

        $model = $this->beforeCallback($model, $request);

        foreach ($request->post() as $attribute => $value) {
            if ($this->isExcludedFieldName($attribute)) {
                continue;
            }

            if ($this->modelHasRelation($model, $attribute)) {
                $this->setRelation($model, $attribute, $value);
            } else {
                $this->setAttribute($model, $attribute, $value);
            }
        }

        $this->withModelEvents ? $model->update() : $model->updateQuietly();

        $model = $this->afterCallback($model, $request);

        if ($this->withEvents) {
            event(new UpdatedCrud($model));
        }

        return $model;
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
