<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Validator;

trait CanBeValidated
{
    protected bool $required = false;

    protected ?string $unique = null;

    protected bool $confirm = false;

    protected array $customValidators = [];

    public function required(bool $required = true): static
    {
        $this->required = $required;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function unique(?string $table = null, ?string $column = null, mixed $except = null): static
    {
        $this->unique = 'unique:' . $table . ',' . ($column ?? $this->name) . ',' . $except;

        return $this;
    }

    public function confirm(bool $confirm = true): static
    {
        $this->confirm = $confirm;

        return $this;
    }

    public function isConfirm(): bool
    {
        return $this->confirm;
    }

    public function customValidator(Validator|string $validator): static
    {
        $this->customValidators[] = $validator;

        return $this;
    }

    public function rules(?Model $model = null): array
    {
        $rules = [];

        if ($this->isRequired()) {
            $rules[] = 'required';
        }

        if ($this->isConfirm()) {
            $rules[] = 'confirmed';
        }

        if (null !== $this->unique) {
            $rules[] = $this->unique;
        }

        return array_merge($rules, $this->customValidators);
    }

    protected function getValidationType()
    {
    }
}
