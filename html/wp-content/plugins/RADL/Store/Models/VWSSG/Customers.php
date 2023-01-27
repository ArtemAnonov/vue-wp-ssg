<?php

namespace RADL\Store\Models\VWSSG;
use RADL\Store\Models\ModelVWSSG;

class Customers extends ModelVWSSG
{
    public $route_base = 'customers';

    public $type = 'customers';

    public $specific_params = [

    ];

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }
}
