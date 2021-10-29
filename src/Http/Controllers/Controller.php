<?php

namespace Davesweb\Dashboard\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as IlluminateController;

class Controller extends IlluminateController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
