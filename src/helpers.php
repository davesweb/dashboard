<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Davesweb\Dashboard\Services\Table;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!function_exists('dashboard_route')) {
    function dashboard_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route(config('dashboard.route-prefix') . $name, $parameters, $absolute);
    }
}

if (!function_exists('full_url_with_query')) {
    function full_url_with_query(array $newQueryParams): string
    {
        /** @var Request $request */
        $request = resolve('request');

        return $request->fullUrlWithQuery($newQueryParams);
    }
}

if (!function_exists('export_icon')) {
    function export_icon(string $exportType): HtmlString
    {
        $icon = match ($exportType) {
            Table::EXPORT_HTML => 'fa fa-file-code',
            Table::EXPORT_CSV  => 'fa fa-file-csv',
            Table::EXPORT_PDF  => 'fa fa-file-pdf',
            Table::EXPORT_XLSX => 'fa fa-file-excel',
            default            => 'fa fa-file',
        };

        return new HtmlString('<i class="' . $icon . '"></i>');
    }
}
