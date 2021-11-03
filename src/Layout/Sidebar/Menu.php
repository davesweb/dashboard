<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Layout\Sidebar;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

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

    public function link(Link|string $titleOrLink, ?string $href = null, string|HtmlString|null $icon = null, ?Menu $submenu = null, int $order = 0): static
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
            ->order($order)
        ;

        return $this;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLinks(): Collection
    {
        return collect($this->links)->sortBy(function (Link $link) {
            return $link->getOrder();
        });
    }

    public function getHref(): string
    {
        return (string) Str::of($this->title)->slug();
    }
}
