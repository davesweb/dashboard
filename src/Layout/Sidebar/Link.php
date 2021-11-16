<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Layout\Sidebar;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class Link
{
    private string $title;

    private ?string $href = null;

    private ?HtmlString $icon = null;

    private ?Menu $submenu = null;

    private int $order = 0;

    private ?string $target = null;

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

    public function getIcon(): ?HtmlString
    {
        return $this->icon;
    }

    public function icon(string|HtmlString|null $icon): self
    {
        $this->icon = $icon instanceof HtmlString || null === $icon ? $icon : new HtmlString($icon);

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

    public function getOrder(): int
    {
        return $this->order;
    }

    public function order(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function hasSubmenu(): bool
    {
        return null !== $this->submenu;
    }

    public function isActive(Request $request): bool
    {
        return $request->fullUrlIs($this->getHref());
    }

    public function target(?string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function targetBlank(): static
    {
        return $this->target('_blank');
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }
}
