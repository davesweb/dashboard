<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Traits;

use Davesweb\Dashboard\Services\Form\Elements\Editor;
use Illuminate\Support\Str;
use Davesweb\Dashboard\Services\Form\Element;
use Davesweb\Dashboard\Services\Form\Elements\Tel;
use Davesweb\Dashboard\Services\Form\Elements\Url;
use Davesweb\Dashboard\Services\Form\Elements\Date;
use Davesweb\Dashboard\Services\Form\Elements\Text;
use Davesweb\Dashboard\Services\Form\Elements\Time;
use Davesweb\Dashboard\Services\Form\Elements\Week;
use Davesweb\Dashboard\Services\Form\Elements\Color;
use Davesweb\Dashboard\Services\Form\Elements\Email;
use Davesweb\Dashboard\Services\Form\Elements\Month;
use Davesweb\Dashboard\Services\Form\Elements\Range;
use Davesweb\Dashboard\Services\Form\Elements\HasOne;
use Davesweb\Dashboard\Services\Form\Elements\Hidden;
use Davesweb\Dashboard\Services\Form\Elements\Search;
use Davesweb\Dashboard\Services\Form\Elements\HasMany;
use Davesweb\Dashboard\Services\Form\Elements\Section;
use Davesweb\Dashboard\Services\Form\Elements\Dropdown;
use Davesweb\Dashboard\Services\Form\Elements\Password;
use Davesweb\Dashboard\Services\Form\Elements\TextArea;
use Davesweb\Dashboard\Services\Form\Elements\BelongsTo;
use Davesweb\Dashboard\Services\Form\Elements\BelongsToMany;
use Davesweb\Dashboard\Services\Form\Elements\DatetimeLocal;
use Davesweb\Dashboard\Services\Form\Elements\Number as NumberElement;

/**
 * @method static element(Element $element)
 */
trait HasAttributeFields
{
    public function section(string $title, ?string $name = null, ?string $description = null): Section
    {
        /** @var Section $section */
        $section = resolve(Section::class, ['name' => $name ?? Str::of($title)->slug()])->title($title)->description($description);

        $this->element($section);

        return $section;
    }

    public function belongsTo(string $name, ?string $label = null, ?string $relation = null): BelongsTo
    {
        /** @var BelongsTo $input */
        $input = resolve(BelongsTo::class)->name($name)->relation($relation ?? $name)->label($label);

        $this->element($input);

        return $input;
    }

    public function belongsToMany(string $name, ?string $label = null, ?string $relation = null): BelongsToMany
    {
        /** @var BelongsToMany $input */
        $input = resolve(BelongsToMany::class)->name($name)->relation($relation ?? $name)->label($label);

        $this->element($input);

        return $input;
    }

    public function color(string $name, ?string $label = null): Color
    {
        /** @var Color $input */
        $input = resolve(Color::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function date(string $name, ?string $label = null): Date
    {
        /** @var Date $input */
        $input = resolve(Date::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function datetimeLocale(string $name, ?string $label = null): DatetimeLocal
    {
        /** @var DatetimeLocal $input */
        $input = resolve(DatetimeLocal::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function dropdown(string $name, ?string $label = null): Dropdown
    {
        /** @var Dropdown $input */
        $input = resolve(Dropdown::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }
    
    public function editor(string $name, ?string $label = null): Editor
    {
        /** @var Editor $input */
        $input = resolve(Editor::class)->name($name)->label($label);
        
        $this->element($input);
        
        return $input;
    }

    public function email(string $name, ?string $label = null): Email
    {
        /** @var Email $input */
        $input = resolve(Email::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function hasMany(string $name, ?string $label = null, ?string $relation = null): HasMany
    {
        /** @var HasMany $input */
        $input = resolve(HasMany::class)->name($name)->relation($relation ?? $name)->label($label);

        $this->element($input);

        return $input;
    }

    public function hasOne(string $name, ?string $label = null, ?string $relation = null): HasOne
    {
        /** @var HasOne $input */
        $input = resolve(HasOne::class)->name($name)->relation($relation ?? $name)->label($label);

        $this->element($input);

        return $input;
    }

    public function hidden(string $name, ?string $label = null): Hidden
    {
        /** @var Hidden $input */
        $input = resolve(Hidden::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function month(string $name, ?string $label = null): Month
    {
        /** @var Month $input */
        $input = resolve(Month::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function number(string $name, ?string $label = null): NumberElement
    {
        /** @var NumberElement $input */
        $input = resolve(NumberElement::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function password(string $name, ?string $label = null): Password
    {
        /** @var Password $input */
        $input = resolve(Password::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function range(string $name, ?string $label = null): Range
    {
        /** @var Range $input */
        $input = resolve(Range::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function search(string $name, ?string $label = null): Search
    {
        /** @var Search $input */
        $input = resolve(Search::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function tel(string $name, ?string $label = null): Tel
    {
        /** @var Tel $input */
        $input = resolve(Tel::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function text(string $name, ?string $label = null): Text
    {
        /** @var Text $input */
        $input = resolve(Text::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function textarea(string $name, ?string $label = null): TextArea
    {
        /** @var TextArea $input */
        $input = resolve(TextArea::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function time(string $name, ?string $label = null): Time
    {
        /** @var Time $input */
        $input = resolve(Time::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function url(string $name, ?string $label = null): Url
    {
        /** @var Url $input */
        $input = resolve(Url::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }

    public function week(string $name, ?string $label = null): Week
    {
        /** @var Week $input */
        $input = resolve(Week::class)->name($name)->label($label);

        $this->element($input);

        return $input;
    }
}
