<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Addons;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class AddonManifest
{
    private Filesystem $files;

    private string $basePath;

    private string $manifestPath;

    public function __construct(Filesystem $files, string $basePath, string $manifestPath)
    {
        $this->files        = $files;
        $this->basePath     = (string) Str::of($basePath)->finish(DIRECTORY_SEPARATOR);
        $this->manifestPath = $manifestPath;
    }

    public function build(): array
    {
        $packages = [];

        if ($this->files->exists($path = $this->getVendorPath() . 'composer' . DIRECTORY_SEPARATOR . 'installed.json')) {
            $installed = json_decode($this->files->get($path), true);

            $packages = $installed['packages'] ?? $installed;
        }

        $this->write($addons = collect($packages)->mapWithKeys(function (array $package) {
            return [$package['name'] => $package['extra']['dashboard'] ?? []];
        })->reject(function (array $addon) {
            return empty($addon);
        })->filter()->toArray());

        return $addons;
    }

    protected function getVendorPath(): string
    {
        return $this->basePath . 'vendor' . DIRECTORY_SEPARATOR;
    }

    protected function write(array $manifest)
    {
        if (!is_writable($dirname = dirname($this->manifestPath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $this->files->replace(
            $this->manifestPath,
            '<?php return ' . var_export($manifest, true) . ';'
        );
    }
}
