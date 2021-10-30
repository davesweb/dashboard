<?php

namespace Davesweb\Dashboard\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('dashboard.users.table');
    }
}
