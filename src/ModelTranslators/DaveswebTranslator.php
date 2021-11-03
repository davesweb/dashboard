<?php

namespace Davesweb\Dashboard\ModelTranslators;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Davesweb\LaravelTranslatable\Traits\HasTranslations;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;

class DaveswebTranslator implements TranslatesModelAttributes
{
    /**
     * @param HasTranslations|Model $model
     */
    public function translate(Model $model, string $locale, string|Closure $attribute): mixed
    {
        if ($attribute instanceof Closure) {
            return call_user_func($attribute, $model, $locale);
        }

        return $model->translate($attribute, $locale);
    }

    public function search(Builder $query, string $field, string $locale, string $searchQuery): Builder
    {
        return $query->orWhereHas('translations', function (Builder $query) use ($field, $locale, $searchQuery) {
            $query
                ->where('locale', '=', $locale)
                ->where($field, 'LIKE', '%' . $searchQuery . '%')
            ;
        });
    }
}
