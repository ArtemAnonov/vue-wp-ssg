<?php

namespace RADL\Store\Models;

class Customers extends BasedModelsWC
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
