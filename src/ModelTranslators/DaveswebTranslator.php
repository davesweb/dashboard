<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\ModelTranslators;

use Closure;
use Illuminate\Support\Str;
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

    /**
     * @param HasTranslations|Model $model
     */
    public function set(Model $model, string $locale, iterable $translations): Model
    {
        $translation         = $model->getTranslation($locale) ?? $model->translations()->newModelInstance();
        $translation->locale = $locale;

        foreach ($translations as $attribute => $value) {
            $setter = 'set' . Str::of($attribute)->camel()->ucfirst();

            if (method_exists($translation, $setter)) {
                call_user_func([$translation, $setter], $value);

                continue;
            }

            $translation->{$attribute} = $value;
        }

        $model->translations()->save($translation);

        return $model;
    }
}
