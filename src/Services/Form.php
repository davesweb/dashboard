<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Davesweb\Dashboard\Services\Form\Element;
use Davesweb\Dashboard\Services\Form\Elements\Section;
use Davesweb\Dashboard\Services\Form\Traits\HasAttributeFields;

class Form
{
    use HasAttributeFields;

    private ?string $route = null;

    private ?string $href = null;

    private bool $media = false;

    private string $method = 'post';

    /**
     * @var Collection|Section[]
     */
    private ?Collection $sections;

    /**
     * @var Collection|Element[]
     */
    private ?Collection $fields;

    private Crud $crud;

    public function __construct(Crud $crud)
    {
        $this->fields   = collect();
        $this->sections = collect();
        $this->crud     = $crud;
    }

    public function hasSections(): bool
    {
        return $this->sections()->count() > 0;
    }

    public function sections(): Collection
    {
        return $this->sections;
    }

    public function hasSectionsOrFields(): bool
    {
        return $this->hasSections() || $this->fields()->count() > 0;
    }

    public function fields(): Collection
    {
        return $this->fields;
    }

    public function route(string $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function href(string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function getAction(): string
    {
        return $this->getHref() ?? (null !== $this->getRoute() ? route($this->getRoute()) : '#');
    }

    public function getValidationRules(?Model $model = null): array
    {
        $rules = [];

        foreach ($this->fields() as $field) {
            if (!empty(($fieldRules = $field->rules($model)))) {
                $rules[$field->getName()] = $fieldRules;
            }
        }

        foreach ($this->sections() as $section) {
            foreach ($section->fields() as $field) {
                if (!empty(($fieldRules = $field->rules($model)))) {
                    $rules[$field->getName()] = $fieldRules;
                }
            }
        }

        return $rules;
    }

    public function method(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMedia(): static
    {
        $this->media = true;

        return $this;
    }

    public function hasMedia(): bool
    {
        return $this->media;
    }

    public function element(Element $element): Element
    {
        if ($element instanceof Section) {
            $this->sections()->push($element);

            return $element;
        }

        if ($this->sections()->count() > 0) {
            $section = $this->sections()->last();

            $section->element($element);

            return $element;
        }

        $this->fields()->push($element);

        return $element;
    }
}
