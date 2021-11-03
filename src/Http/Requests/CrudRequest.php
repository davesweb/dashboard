<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Requests;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Http\FormRequest;

abstract class CrudRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getCrudLocale(): string
    {
        $locale = $this->get('locale', Cookie::get('crud-locale', config('app.default_locale', config('app.locale'))));

        Cookie::queue('crud-locale', $locale, 525600);

        return $locale;
    }
}
