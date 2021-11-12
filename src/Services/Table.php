<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use Davesweb\Dashboard\Services\Table\Columns\Column;
use Davesweb\Dashboard\Services\Table\Traits\HasColumns;
use Davesweb\Dashboard\Services\Table\Traits\HasDateColumns;
use Davesweb\Dashboard\Services\Table\Traits\HasRelationshipColumns;

class Table
{
    use HasColumns;
    use HasDateColumns;
    use HasRelationshipColumns;

    public const EXPORT_CSV  = 'csv';
    public const EXPORT_XLSX = 'xlsx';
    public const EXPORT_PDF  = 'pdf';
    public const EXPORT_HTML = 'html';

    /**
     * @var Column[]
     */
    private iterable $columns = [];

    private Crud $crud;

    private iterable $exports = [];

    public function __construct(Crud $crud)
    {
        $this->crud = $crud;
    }

    public function hasSearch(): bool
    {
        foreach ($this->columns as $column) {
            if ($column->isSearchable()) {
                return true;
            }
        }

        return false;
    }

    public function hasTranslations(): bool
    {
        foreach ($this->columns as $column) {
            if ($column->isTranslated()) {
                return true;
            }
        }

        return false;
    }

    public function export(string $exportType): static
    {
        if (!in_array($exportType, $this->exports, true)) {
            $this->exports[] = $exportType;
        }

        return $this;
    }

    public function exportCsv(): static
    {
        return $this->export(self::EXPORT_CSV);
    }

    public function exportXlsx(): static
    {
        return $this->export(self::EXPORT_XLSX);
    }

    public function exportPdf(): static
    {
        return $this->export(self::EXPORT_PDF);
    }

    public function exportHtml(): static
    {
        return $this->export(self::EXPORT_HTML);
    }

    public function exports(): static
    {
        return $this
            ->exportCsv()
            ->exportXlsx()
            ->exportPdf()
            ->exportHtml()
        ;
    }

    public function hasExports(): bool
    {
        return count($this->exports) > 0;
    }

    public function getExports(): iterable
    {
        return $this->exports;
    }
}
