<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Layout\Sidebar;

use Illuminate\Support\Collection;

class Sidebar
{
    private static ?Sidebar $sidebar = null;

    private array $menus = [];

    public static function factory(): static
    {
        if (null === self::$sidebar) {
            self::$sidebar = new static();
        }

        return self::$sidebar;
    }

    public function menu(Menu $menu, ?int $order = null): static
    {
        $this->menus[] = ['menu' => $menu, 'order' => $order];

        return $this;
    }

    public function getMenus(): Collection
    {
        return collect($this->menus)->sortBy('order')->map(function (array $menu) {
            return $menu['menu'];
        });
    }

    public function clear(): static
    {
        $this->menus = [];

        return $this;
    }
}
