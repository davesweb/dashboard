# CRUD trashed

The `trashed` method of your CRUD class defines what the table overview of all soft-deleted models looks like and adds 
optional predefined filters to the table view. The method receives a `Davesweb\Dashboard\Services\Table` object as 
its only parameter, and it must return nothing (`void`).

> Even if your CRUD defines a `trashed` action, this method is optional. If this method is not defined, the `index`
> method is used instead.

This method works exactly the same as the `index` method: [CRUD index](index.html).