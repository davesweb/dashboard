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
        if ($this->fireEvents) {
            event(new SavingCrud($model));
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

        $model->save();

        $model = $this->afterCallback($model, $request);

        if ($this->fireEvents) {
            event(new SavedCrud($model));
        }

        return $model;
    }

    public function storeQuietly(StoreCrudRequest $request, Model $model): Model
    {
        $this->withoutEvents();

        $model = $this->store($request, $model);

        $this->withEvents();

        return $model;
    }
}
