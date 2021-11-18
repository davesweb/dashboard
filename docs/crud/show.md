# CRUD show

The `show` method of your CRUD class defines what the detail view of a model looks like. The method receives a 
`Davesweb\Dashboard\Services\Table` object as its only parameter, and it must return nothing (`void`).

> Even if your CRUD defines a `show` action, this method is optional. If this method is not defined, the `index`
> method is used instead.

This method works exactly the same as the `index` method: [CRUD index](index.html), with the exception that the 
searching, ordering and filtering is ignored.