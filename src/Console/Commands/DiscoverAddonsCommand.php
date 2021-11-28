<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Davesweb\Dashboard\Addons\AddonManifest;

class DiscoverAddonsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dashboard:addons';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Discover and register any addons for the dashboard.';

    public function handle(AddonManifest $manifest): int
    {
        $addons = $manifest->build();

        foreach (array_keys($addons) as $package) {
            $this->line("Discovered dashboard addon: <info>{$package}</info>");
        }

        return self::SUCCESS;
    }
}
