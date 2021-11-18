<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class HasOne extends Dropdown
{
    private string $relation;

    private bool $translatedRelation = false;

    private string|Closure|null $optionValue = null;

    private string|Closure $optionTitle;

    private ?Model $model = null;

    private string $locale;

    public function relation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function optionTitle(string|Closure $optionTitle): static
    {
        $this->optionTitle = $optionTitle;

        return $this;
    }

    public function getOptionTitle(): string|Closure
    {
        return $this->optionTitle;
    }

    public function optionValue(string|Closure|null $optionValue): static
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    public function getOptionValue(): string|Closure|null
    {
        return $this->optionValue;
    }

    public function translatedRelation(bool $translated = true): static
    {
        $this->translatedRelation = true;

        return $this;
    }

    public function isTranslatedRelation(): bool
    {
        return $this->translatedRelation;
    }

    public function getOptions(): iterable
    {
        $options      = [];
        $relation     = $this->getRelationModel($this->model);
        $allRelations = $relation->get();

        foreach ($allRelations as $optionRelation) {
            $keyGetter = $this->getOptionValue() ?? $this->getOptionTitle();

            if ($this->isTranslatedRelation()) {
                $value = $this->getTranslator()->translate($optionRelation, $this->locale, $this->getOptionTitle());
            } else {
                if ($this->getOptionTitle() instanceof Closure) {
                    $value = call_user_func($this->getOptionTitle(), $optionRelation);
                } else {
                    $getter = 'get' . Str::of(($this->getOptionTitle()))->camel()->ucfirst();
                    if (method_exists($optionRelation, $getter)) {
                        $value = call_user_func([$optionRelation, $getter]);
                    } else {
                        $value = $optionRelation->{$this->getOptionTitle()};
                    }
                }
            }

            if ($keyGetter instanceof Closure) {
                $key = call_user_func($keyGetter, $optionRelation);
            } else {
                $getter = 'get' . Str::of(($keyGetter))->camel()->ucfirst();
                if (method_exists($optionRelation, $getter)) {
                    $key = call_user_func([$optionRelation, $getter]);
                } else {
                    $key = $optionRelation->{$keyGetter};
                }
            }

            $options[$key] = $value;
        }

        return $options;
    }

    public function getValue(?Model $model, string $locale): mixed
    {
        /** @var Collection|Model $value */
        $value = parent::getValue($model, $locale);

        return $value instanceof Model ? $value->getKey() : $value->map(function (Model $model) {
            return $model->getKey();
        })->toArray();
    }

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        $this->model  = $model;
        $this->locale = $locale;

        return parent::render($model, $locale, $availableLocales, $inSection);
    }

    private function getRelationModel(Model $model): Builder
    {
        $getter = 'get' . Str::of($this->relation)->camel()->ucfirst();

        /* @var Relation $relation */
        if (method_exists($model, $getter)) {
            $relation = call_user_func([$model, $getter]);
        } else {
            $relation = $model->{$this->relation}();
        }

        return $relation->newModelInstance()->newQuery();
    }
}
