<?php

namespace RADL\Store\Models;

class Checkout extends BasedModelsWC
{
    public $apiType = '/wc/store/v1/';

    public $route_base = 'checkout';

    public $type = 'checkout';

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }
}
