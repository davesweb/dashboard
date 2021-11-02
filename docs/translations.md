# Translations

Davesweb dashboard comes with built in support for our translation package `davesweb/laravel-translations`. 
The documentation of which is available on [Github](https://github.com/davesweb/laravel-translatable).

## Other translation packages

You can use any translation method or package that you like. If you use a different method or package than 
`davesweb/laravel-translations`, simply create a custom translator in your app that knows how to translate 
model properties. You can do so by creating a class that implements `Davesweb\Dashboard\Contracts\TranslatesModelAttributes`
and telling the dashboard to use that translator by setting it in the `dashboard.php` config file.

```php
<?php

return [
    /*
     * The translator to use for translating model attributes.
     */
    'translator' => \Your\Custom\Translator::class,
];
```

The `TranslatesModelAttributes` interface has a single method named `translate`. This method should return the 
translation of the requested attribute on the given model in the requested locale. The attribute can also be a 
`\Closure`, and in that case the method should call the closure with the `$model` as the first argument and the 
`$locale` as the second argument like this: `call_user_func($attribute, $model, $locale)`.

## Available translations

> Todo: use HtmlString for these icons as well
> 
The available translations must be defined in the `app.php` config file in the following format:

```php
<?php

return [
    'available_locales' => [
        'nl' => [
            'icon' => 'flag-icon flag-icon-nl',
            'name' => 'Nederlands',
        ],
        'en' => [
            'icon' => 'flag-icon flag-icon-en',
            'name' => 'English',
        ],
        'de' => [
            'icon' => 'flag-icon flag-icon-de',
            'name' => 'Deutsch',
        ],
    ],
];
```

There must also be a default locale defined in the same config file:

```php
<?php

return [
    'default_locale' => 'nl',
];
```