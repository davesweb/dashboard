<?php

namespace Davesweb\Dashboard\Layout\Sidebar;

use Illuminate\Support\Str;

class Menu
{
    private ?string $title = null;

    private array $links = [];

    public static function make(?string $title = null): static
    {
        return (new static())->title($title);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function title(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function link(Link|string $titleOrLink, ?string $href = null, ?string $icon = null, ?Menu $submenu = null): static
    {
        if ($titleOrLink instanceof Link) {
            $this->links[] = $titleOrLink;

            return $this;
        }

        $this->links[] = (new Link())
            ->title($titleOrLink)
            ->href($href)
            ->icon($icon)
            ->submenu($submenu)
        ;

        return $this;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getHref(): string
    {
        return (string) Str::of($this->title)->slug();
    }
}
