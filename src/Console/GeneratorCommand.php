<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand as IlluminateGeneratorCommand;

abstract class GeneratorCommand extends IlluminateGeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected function sortImports($stub)
    {
        if (preg_match('/(?P<imports>(?:use [^;]+;$\n?)+)/m', $stub, $match)) {
            $imports = explode("\n", trim($match['imports']));

            usort($imports, function (string $first, string $second) {
                return strlen($first) - strlen($second);
            });

            return str_replace(trim($match['imports']), implode("\n", $imports), $stub);
        }

        return $stub;
    }

    protected function getCrudLocation(): string
    {
        $locations = config('dashboard.crud.locations', []);

        if (0 === count($locations)) {
            // No locations defined, return default
            return app_path('Crud');
        }

        // Return the first defined
        return array_key_first($locations);
    }

    protected function getCrudNamespace(?string $location = null): string
    {
        $defaultNamespace = $this->rootNamespace() . 'Crud\\';

        // Without location, return the default namespace
        if (null === $location) {
            return $defaultNamespace;
        }

        $locations = config('dashboard.crud.locations', []);

        return (string) Str::of($locations[$location] ?? $defaultNamespace)->finish('\\');
    }
}
