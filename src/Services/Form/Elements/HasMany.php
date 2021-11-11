<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

class HasMany extends HasOne
{
    protected bool $multiple = true;
}
