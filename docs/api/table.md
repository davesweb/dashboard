# Table

> Class: Davesweb\Dashboard\Services\Table

The table class adds a way to define what the index, trashed and show views should look like.

## Methods

### column

The `column` methods adds a column to the table and returns the added `Column` object.

**Parameters**

|Name|Type|Required|Description|
|---|---|---|---|
|`title`|`string`|Yes|The title of the column. This is shown in the table header to identify the column.|
|`content`|`string` or `\Closure`|No|This parameter defines how the content is shown. If it is a string, this is used as the attribute to show. If it is a `Closure` the result of the closure is shown. If this is left empty the `title` is converted into snake case and used as the name.|