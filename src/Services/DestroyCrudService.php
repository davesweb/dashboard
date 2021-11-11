<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Events\DestroyedCrud;
use Davesweb\Dashboard\Events\DestroyingCrud;
use Davesweb\Dashboard\Http\Requests\DestroyCrudRequest;

class DestroyCrudService extends CrudService
{
    public function destroy(DestroyCrudRequest $request, Model $model): bool
    {
        if ($this->withEvents) {
            event(new DestroyingCrud($model));
        }

        $model = $this->beforeCallback($model, $request);

        $result = $model->delete();

        $model = $this->afterCallback($model, $request);

        if ($this->withEvents) {
            event(new DestroyedCrud($model));
        }

        return $result;
    }

    public function destroyQuietly(DestroyCrudRequest $request, Model $model): bool
    {
        $this->withoutEvents();

        $result = $this->destroy($request, $model);

        $this->withEvents();

        return $result;
    }
}
