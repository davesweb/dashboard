<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form;

use Davesweb\Dashboard\Contracts\TranslatesModelAttributes;

interface Translatable
{
    public function translated(): static;

    public function isTranslatable(): bool;

    public function setTranslator(TranslatesModelAttributes $translator): static;

    public function getTranslator(): TranslatesModelAttributes;
}
