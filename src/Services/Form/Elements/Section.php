<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Services\Form\Element;
use Davesweb\Dashboard\Services\Form\Translatable;
use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;
use Davesweb\Dashboard\Services\Form\Traits\HasAttributeFields;

class Section extends Element implements Translatable
{
    use HasAttributeFields;

    protected bool $translated = false;

    private Collection $fields;

    private string $name;

    private ?string $title = null;

    private ?string $description = null;

    public function __construct(string $name)
    {
        $this->name   = $name;
        $this->fields = collect();
    }

    public function element(Element $element): Element
    {
        $this->fields->push($element);

        return $element;
    }

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        $this->fields->each(function (Element $element) {
            $element->translated($this->isTranslatable());
        });

        return new HtmlString(view('dashboard::crud.form.section', [
            'fields'       => $this->fields,
            'name'         => $this->name,
            'title'        => $this->title,
            'description'  => $this->description,
            'formLocale'   => $locale,
            'translatable' => $this->isTranslatable(),
            'model'        => $model,
        ])->render());
    }

    public function namne(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function title(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function description(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function translated(bool $translated = true): static
    {
        $this->translated = $translated;

        return $this;
    }

    public function isTranslatable(): bool
    {
        return $this->translated;
    }

    public function setTranslator(TranslatesModelAttributes $translator): static
    {
        // Not needed for this class

        return $this;
    }

    public function getTranslator(): TranslatesModelAttributes
    {
        // Not needed for this class

        throw new Exception();
    }

    public function fields(): Collection
    {
        return $this->fields;
    }
}
