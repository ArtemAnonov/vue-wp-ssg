<?php

namespace RADL\Store\Models;

class Orders extends BasedModelsWC
{
    public $route_base = 'orders';

    public $type = 'orders';



    public $specific_params = [
        "per_page" => 8,
        "page" => 1,
    ];

}
