<?php

namespace Davesweb\Dashboard\Layout\Sidebar;

class Link
{
    private string $title;

    private ?string $href = null;

    private ?string $icon = null;

    private ?Menu $submenu = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function href(?string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function icon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getSubmenu(): ?Menu
    {
        return $this->submenu;
    }

    public function submenu(?Menu $submenu): self
    {
        $this->submenu = $submenu;

        return $this;
    }

    public function hasSubmenu(): bool
    {
        return null !== $this->submenu;
    }
}
