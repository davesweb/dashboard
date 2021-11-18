# CRUD Index

The `index` method of your CRUD class defines what the table overview of all models looks like and adds optional 
predefined filters to the table view. The method receives a `Davesweb\Dashboard\Services\Table` object as its only 
parameter and it must return nothing (`void`).

```php
<?php

namespace App\Crud;

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;

class MyCrud extends Crud
{
    public function index(Table $table): void
    {
        // Define the table view
    }
    
    // Other methods
}
```

> If your CRUD defines a `trashed` action, the dashboard calls the `trashed` method in your CRUD class for the overview 
> of soft-deleted models. This `trashed` method works exactly the same as the `index` method. If there is a `trashed` 
> action, but you don't define a `trashed` method in your CRUD class, the `index` method is used for both actions.

> If your CRUD defines a `show` action, the dashboard calls the `show` method in your CRUD class for the detail view
> of a model. This `show` method works exactly the same as the `index` method, except things like searching, ordering 
> and filtering are ignored for this view. If there is a `show` action, but you don't define a `show` method in your 
> CRUD class, the `index` method is used for this action as well.

## Columns

You define what the table looks like by adding columns to the table. There are many different columns that can be 
added and each column has multiple options as well. You can read the details about this in the [Table](../api/table.html) 
section. Below is a short summary of the most commonly used options.

### Text columns

### Columns with translated content

### Relationship columns

### Actions and action columns

## Searching

## Filters

A filter is essentially a predefined search query. Each filter is shown in a dropdown menu in the overview table, and
when selected that filter is executed on the overview of models.

You can add filters to your table overview by calling the `filter` method on the `Table` object. It requires a name, 
a filter and an optional title.

**Example**

Let's say you have a BlogPost model in your application and in your dashboard you have an overview of all posts. Each 
post can have a status, `draft` or `published`, and you want to have an easy way to show all published posts and all 
draft posts. In that case you can simply define two filters that do exactly that:

```php
<?php

namespace App\Crud;

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;
use Illuminate\Database\Eloquent\Builder;

class BlogPosts extends Crud
{
    public function index(Table $table): void
    {
        // Define the table columns
        
        $table->filter('published', function(Builder $query) {
            $query->where('status', '=', 'published');
        }, __('Published posts'));
        
        $table->filter('drafts', function(Builder $query) {
            $query->where('status', '=', 'draft');
        }, __('Drafts'));
    }
    
    // Other methods
}
```

## Exports

## Ordering