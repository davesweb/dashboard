<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Services\Form\Element;
use Davesweb\Dashboard\Services\Form\Translatable;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;
use Davesweb\Dashboard\Services\Form\Traits\CanBeValidated;

abstract class Input extends Element implements Translatable
{
    use CanBeValidated;

    protected string $type = 'text';

    private TranslatesModelAttributes $translator;

    private ?string $name = null;

    private ?string $label = null;

    private mixed $value = null;

    private ?string $placeholder = null;

    private bool $autofocus = false;

    private bool $translated = false;

    public function __construct(TranslatesModelAttributes $translator)
    {
        $this->setTranslator($translator);
    }

    public function name(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function label(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label ?? (string) Str::of($this->name)->snake(' ')->ucfirst();
    }

    public function value(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(?Model $model, string $locale): mixed
    {
        if (null === $model) {
            return '';
        }

        $value = $this->value ?? $this->name;

        if ($this->translated) {
            return old($this->name, $this->translator->translate($model, $locale, $value));
        }

        if ($value instanceof Closure) {
            return call_user_func_array($value, [$model, $locale]);
        }

        $getter = 'get' . Str::of($value)->camel()->ucfirst();

        if (method_exists($model, $getter)) {
            return (string) call_user_func([$model, $getter]);
        }

        return $model->{$value};
    }

    public function placeholder(?string $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function autofocus(bool $autofocus = true): static
    {
        $this->autofocus = $autofocus;

        return $this;
    }

    public function hasAutofocus(): bool
    {
        return $this->autofocus;
    }

    public function translated(): static
    {
        $this->translated = true;

        return $this;
    }

    public function isTranslatable(): bool
    {
        return $this->translated;
    }

    public function setTranslator(TranslatesModelAttributes $translator): static
    {
        $this->translator = $translator;

        return $this;
    }

    public function getTranslator(): TranslatesModelAttributes
    {
        return $this->translator;
    }

    public function render(?Model $model, string $locale, array $availableLocales = []): HtmlString
    {
        // todo this can be better
        $secondary = new HtmlString('');

        if ($this->isConfirm()) {
            $secondary = new HtmlString(view('dashboard::crud.form.input', [
                'availableLocales' => $availableLocales,
                'type'             => $this->type,
                'model'            => $model,
                'value'            => function (?Model $model, string $locale) {
                    return $this->getValue($model, $locale);
                },
                'formLocale'       => $locale,
                'name'             => $this->getName() . '_confirmation',
                'label'            => __('Confirm') . ' ' . $this->getLabel(),
                'placeholder'      => $this->getPlaceholder(),
                'required'         => $this->isRequired(),
                'autofocus'        => $this->hasAutofocus(),
                'translatable'     => $this->isTranslatable(),
            ])->render());
        }

        return new HtmlString(view('dashboard::crud.form.input', [
            'availableLocales' => $availableLocales,
            'type'             => $this->type,
            'model'            => $model,
            'value'            => function (?Model $model, string $locale) {
                return $this->getValue($model, $locale);
            },
            'formLocale'       => $locale,
            'name'             => $this->getName(),
            'label'            => $this->getLabel(),
            'placeholder'      => $this->getPlaceholder(),
            'required'         => $this->isRequired(),
            'autofocus'        => $this->hasAutofocus(),
            'translatable'     => $this->isTranslatable(),
        ])->render() . $secondary->toHtml());
    }
}
