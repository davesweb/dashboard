<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services;

use SplFileInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Collection;

class CrudFinder
{
    public function findAllByLocations(array $locations): Collection
    {
        $cruds = collect();

        foreach ($locations as $location => $namespace) {
            $cruds->push($this->findAllByLocation($location, $namespace));
        }

        return $cruds->flatten();
    }

    public function findAllByLocation(string $location, string $namespace): array
    {
        $cruds = [];

        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($location));

        /** @var SplFileInfo $file */
        foreach ($directory as $path => $file) {
            if (!$file->isFile() && 'php' !== !$file->getExtension()) {
                continue;
            }

            $classname = $namespace . '\\' . Str::of($path)->replace([$location, '.php', '/'], '')->ltrim('\\');

            if (class_exists($classname)) {
                $object = resolve($classname);

                if (!$object instanceof Crud) {
                    continue;
                }

                $cruds[] = $object;
            }
        }

        return $cruds;
    }

    public function findCrudByRequest(Request $request): ?Crud
    {
        $locations = array_merge(config('dashboard.crud.locations', []), [__DIR__ . '/../Crud' => 'Davesweb\\Dashboard\\Crud']);

        //if (app()->runningUnitTests()) {
        //    $locations[__DIR__ . '/../../tests/Crud'] = '\\Davesweb\\Dashboard\\Tests\\Crud';
        //}

        /** @var Crud $crud */
        foreach ($this->findAllByLocations($locations) as $crud) {
            if ($crud->hasRoute($request->route())) {
                return $crud;
            }
        }

        return null;
    }
}
