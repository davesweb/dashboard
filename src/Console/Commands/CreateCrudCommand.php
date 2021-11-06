<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Console\Commands;

use Illuminate\Support\Str;
use Davesweb\Dashboard\Console\GeneratorCommand;

class CreateCrudCommand extends GeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dashboard:crud
        {name : The name of the model this crud class should be created for.}
        {--c|-controller : Also create a crud controller for this model.}
        {--m|-model : Also create a the model. This will also create the migration file for this model.}
        {--t|-translate : Indicates the model is translatable.}
        {--a|-all : Use all possible options.}
        {--x|-all-except : Use all possible options except the crud controller.}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new crud class for the given model.';

    public function handle()
    {
        $nameInput = (string) Str::of($this->getNameInput())->replace([$this->rootNamespace() . 'Models', $this->rootNamespace()], '')->ltrim('\\');

        $name = $this->qualifyClass($this->getCrudNamespace() . $nameInput);

        $path = $this->getPath($name);

        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info('Crud created successfully.');

        return self::SUCCESS;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $classPrefix = '';
        if (is_dir(app_path('Models'))) {
            $classPrefix = 'Models\\';
        }

        $model = $this->qualifyClass($classPrefix . $this->getNameInput());

        $path = $this->getPath($model);

        return $this->replaceNamespace($stub, $name)->replaceModel($stub, $model, $path)->replaceClass($stub, $name);
    }

    protected function replaceModel(string &$stub, string $name, string $path): static
    {
        $namespace = $name;

        $class = str_replace($this->getNamespace($name) . '\\', '', $name) . '::class';

        $stub = str_replace(['DummyModel', '{{ model }}', '{{model}}'], $class, $stub);
        $stub = str_replace(['DummyModelNamesapce', '{{ modelnamespace }}', '{{modelnamespace}}'], $namespace, $stub);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/Crud.stub';
    }
}
