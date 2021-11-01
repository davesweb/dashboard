# Crud

Crud classes are the base of the dashboard, and the only thing that is required in order to have a 
working dashboard.

## Actions

The crud actions are the actions that are available for the model. By default, these actions are:

- **Index action**: the index action registers the index route which shows a paginated overview of all model objects.
- **Trashed action**: the trashed action registers the trashed index route which shows a paginated overview of all soft deleted model objects.
- **View action**: the view action registers the view route which shows a detailed page of a single model.
- **Create action**: the create action registers the create and store routes for creating and storing a new model object.
- **Edit action**: the edit action registers the edit and update routes for editing and updating a model object.
- **Destroy action**: the destroy action registers the destroy route which deletes a model object.
- **Destroy hard action**: the destroy hard action registers the destroy hard route which permanently deletes soft deleted model objects.

By default, the dashboard uses all actions except the _trashed_ and _destroy hard_ actions, which are only registered 
if the model uses the `Illuminate\Database\Eloquent\SoftDeletes` trait. You may overwrite this behavior by defining a 
`actions` method in your crud class which returns an array of all available actions.

```php
<?php

use Davesweb\Dashboard\Services\Crud;

class MyCrud extends Crud
{
    public function actions(): array
    {
        return [
            self::ACTION_INDEX, 
            self::ACTION_VIEW, 
            self::ACTION_CREATE, 
            self::ACTION_UPDATE, 
            self::ACTION_DESTROY
        ];
    }
}
```

## Searching

## Methods

### Index

The `index` method of a Crud class defines what the overview of models looks like, and optionally 
also the overview of trashed models, and the detail view. It is required to implement this method 
in your Crud class.

The method receives a `Davesweb\Dashboard\Services\Table` object which you can use to configure 
the views.

```php
<?php

use Davesweb\Dashboard\Services\Crud;
use Davesweb\Dashboard\Services\Table;

class MyCrud extends Crud
{
    public function index(Table $table): void
    {
        // Configure the table view
    }
}
```

#### Normal column

To add a column to the view, use the `$table->column()` method. This 
method returns a `Davesweb\Dashboard\Services\Table\Column` object. You can add most of the 
details to the method itself, or configure the object the method returns.

#### Action column

The action column is the column that holds the actions a user can perform on the model, like 
viewing details or editing the model. You can define your own actions by calling 
`$table->actionColumn()` and passing along an array of `Davesweb\Dashboard\Services\Table\Action`
actions.

You can also call `$table->defaultActionsColumn()` and the table will automatically generate 
the actions for the model based on the actions configured in the Crud class and on the 
current request.

### Trashed

The `trashed` method of a Crud class defines what the overview of soft deleted models looks like. 
It is not required to implement this method in your own crud classes. If your crud class has a 
trashed route and this method isn't implemented it uses the configuration from the `index` method
instead.

This method works in the same way as the `index` method.