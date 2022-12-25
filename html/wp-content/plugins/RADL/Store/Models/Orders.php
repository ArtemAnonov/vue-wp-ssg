<?php

namespace RADL\Store\Models;

class Orders extends BasedModelsWC
{
    public $route_base = 'orders';

    public $type = 'orders';

    public $specific_params = [

    ];
    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }
}
