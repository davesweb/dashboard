<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

class DateTimeColumn extends DateColumn
{
    protected string $format = '%d %B %Y %H:%M';
}
